<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Log;

class RoleManagement extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';
    #[Url]
    public $sortField = 'name';
    #[Url]
    public $sortDirection = 'asc';
    public $selectedRoles = [];
    public $name, $permissions = [];
    public $editId = null;
    public $showTrashed = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function create()
    {
        $this->authorize('role-create');
        $validated = $this->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array|exists:permissions,name',
        ], [
            'name.required' => 'The role name is required.',
            'name.unique' => 'This role name is already taken.',
            'permissions.exists' => 'One or more selected permissions are invalid.',
        ]);

        try {
            $role = Role::create(['name' => $validated['name'], 'guard_name' => 'web']);
            $role->syncPermissions($this->permissions);
            $this->resetInputFields();
            $this->dispatch('notify', message: 'Role created successfully.', type: 'success');
        } catch (\Exception $e) {
            Log::error('Role creation failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to create role.', type: 'error');
        }
    }

    public function edit($id)
    {
        $this->authorize('role-edit');
        $role = Role::findOrFail($id);
        $this->editId = $id;
        $this->name = $role->name;
        $this->permissions = $role->getPermissionNames()->toArray();
    }

    public function update()
    {
        $this->authorize('role-edit');
        $validated = $this->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$this->editId,
            'permissions' => 'array|exists:permissions,name',
        ], [
            'name.required' => 'The role name is required.',
            'name.unique' => 'This role name is already taken.',
            'permissions.exists' => 'One or more selected permissions are invalid.',
        ]);

        try {
            $role = Role::findOrFail($this->editId);
            $role->update(['name' => $validated['name']]);
            $role->syncPermissions($this->permissions);
            $this->resetInputFields();
            $this->editId = null;
            $this->dispatch('notify', message: 'Role updated successfully.', type: 'success');
        } catch (\Exception $e) {
            Log::error('Role update failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to update role.', type: 'error');
        }
    }

    public function delete($id)
    {
        $this->authorize('role-delete');
        try {
            Role::findOrFail($id)->delete();
            $this->dispatch('notify', message: 'Role deleted successfully.', type: 'success');
        } catch (\Exception $e) {
            Log::error('Role deletion failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to delete role.', type: 'error');
        }
    }

    public function bulkDelete()
    {
        $this->authorize('role-delete');
        if (empty($this->selectedRoles)) {
            $this->dispatch('notify', message: 'No roles selected for deletion.', type: 'warning');
            return;
        }
        try {
            Role::whereIn('id', $this->selectedRoles)->delete();
            $this->selectedRoles = [];
            $this->dispatch('notify', message: 'Selected roles deleted successfully.', type: 'success');
        } catch (\Exception $e) {
            Log::error('Bulk role deletion failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to delete selected roles.', type: 'error');
        }
    }

    public function restore($id)
    {
        $this->authorize('role-restore');
        try {
            Role::withTrashed()->findOrFail($id)->restore();
            $this->dispatch('notify', message: 'Role restored successfully.', type: 'success');
        } catch (\Exception $e) {
            Log::error('Role restoration failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to restore role.', type: 'error');
        }
    }

    public function export($format)
    {
        $this->authorize('role-export');
        $roles = $this->getRolesQuery()->get();

        try {
            if ($format === 'pdf') {
                $pdf = Pdf::loadView('exports.roles', ['roles' => $roles]);
                return response()->streamDownload(fn() => print($pdf->output()), 'roles.pdf');
            } elseif ($format === 'excel') {
                return Excel::download(new class($roles) implements \Maatwebsite\Excel\Concerns\FromCollection {
                    private $roles;
                    public function __construct($roles) { $this->roles = $roles; }
                    public function collection() { return $this->roles; }
                }, 'roles.xlsx');
            }
        } catch (\Exception $e) {
            Log::error('Role export failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to export roles.', type: 'error');
        }
    }

    private function getRolesQuery()
    {
        $query = $this->showTrashed ? Role::withTrashed() : Role::query();
        return $query->where('name', 'like', '%'.$this->search.'%')
                     ->orderBy($this->sortField, $this->sortDirection)
                     ->with('permissions');
    }

    private function resetInputFields()
    {
        $this->name = null;
        $this->permissions = [];
    }

    public function mount()
    {
        $this->permissions = Permission::all();
    }

    public function render()
    {
        $this->authorize('role-list');
        $roles = $this->getRolesQuery()->paginate(10);
        Log::info('Roles count: ' . $roles->count());
        return view('livewire.role-management', [
            'roles' => $roles,
            'permissions' => $this->permissions,
        ]);
    }
}