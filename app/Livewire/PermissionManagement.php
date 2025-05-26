<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Log;

class PermissionManagement extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';
    #[Url]
    public $sortField = 'name';
    #[Url]
    public $sortDirection = 'asc';
    public $selectedPermissions = [];
    public $name;
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
        $this->authorize('permission-create');
        $validated = $this->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ], [
            'name.required' => 'The permission name is required.',
            'name.unique' => 'This permission name is already taken.',
        ]);

        try {
            Permission::create(['name' => $validated['name'], 'guard_name' => 'web']);
            $this->resetInputFields();
            $this->dispatch('notify', message: 'Permission created successfully.', type: 'success');
        } catch (\Exception $e) {
            Log::error('Permission creation failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to create permission.', type: 'error');
        }
    }

    public function edit($id)
    {
        $this->authorize('permission-edit');
        $permission = Permission::findOrFail($id);
        $this->editId = $id;
        $this->name = $permission->name;
    }

    public function update()
    {
        $this->authorize('permission-edit');
        $validated = $this->validate([
            'name' => 'required|string|max:255|unique:permissions,name,'.$this->editId,
        ], [
            'name.required' => 'The permission name is required.',
            'name.unique' => 'This permission name is already taken.',
        ]);

        try {
            $permission = Permission::findOrFail($this->editId);
            $permission->update(['name' => $validated['name']]);
            $this->resetInputFields();
            $this->editId = null;
            $this->dispatch('notify', message: 'Permission updated successfully.', type: 'success');
        } catch (\Exception $e) {
            Log::error('Permission update failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to update permission.', type: 'error');
        }
    }

    public function delete($id)
    {
        $this->authorize('permission-delete');
        try {
            Permission::findOrFail($id)->delete();
            $this->dispatch('notify', message: 'Permission deleted successfully.', type: 'success');
        } catch (\Exception $e) {
            Log::error('Permission deletion failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to delete permission.', type: 'error');
        }
    }

    public function bulkDelete()
    {
        $this->authorize('permission-delete');
        if (empty($this->selectedPermissions)) {
            $this->dispatch('notify', message: 'No permissions selected for deletion.', type: 'warning');
            return;
        }
        try {
            Permission::whereIn('id', $this->selectedPermissions)->delete();
            $this->selectedPermissions = [];
            $this->dispatch('notify', message: 'Selected permissions deleted successfully.', type: 'success');
        } catch (\Exception $e) {
            Log::error('Bulk permission deletion failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to delete selected permissions.', type: 'error');
        }
    }

    public function restore($id)
    {
        $this->authorize('permission-restore');
        try {
            Permission::withTrashed()->findOrFail($id)->restore();
            $this->dispatch('notify', message: 'Permission restored successfully.', type: 'success');
        } catch (\Exception $e) {
            Log::error('Permission restoration failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to restore permission.', type: 'error');
        }
    }

    public function export($format)
    {
        $this->authorize('permission-export');
        $permissions = $this->getPermissionsQuery()->get();

        try {
            if ($format === 'pdf') {
                $pdf = Pdf::loadView('exports.permissions', ['permissions' => $permissions]);
                return response()->streamDownload(fn() => print($pdf->output()), 'permissions.pdf');
            } elseif ($format === 'excel') {
                return Excel::download(new class($permissions) implements \Maatwebsite\Excel\Concerns\FromCollection {
                    private $permissions;
                    public function __construct($permissions) { $this->permissions = $permissions; }
                    public function collection() { return $this->permissions; }
                }, 'permissions.xlsx');
            }
        } catch (\Exception $e) {
            Log::error('Permission export failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to export permissions.', type: 'error');
        }
    }

    private function getPermissionsQuery()
    {
        $query = $this->showTrashed ? Permission::withTrashed() : Permission::query();
        return $query->where('name', 'like', '%'.$this->search.'%')
                     ->orderBy($this->sortField, $this->sortDirection);
    }

    private function resetInputFields()
    {
        $this->name = null;
    }

    public function render()
    {
        $this->authorize('permission-list');
        $permissions = $this->getPermissionsQuery()->paginate(10);
        Log::info('Permissions count: ' . $permissions->count());
        return view('livewire.permission-management', [
            'permissions' => $permissions,
        ]);
    }
}