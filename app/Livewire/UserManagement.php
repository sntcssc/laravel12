<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserManagement extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';
    #[Url]
    public $sortField = 'name';
    #[Url]
    public $sortDirection = 'asc';
    #[Url]
    public $filterStatus = '';
    public $selectedUsers = [];
    public $name, $staff_id, $phone, $joining_date, $email, $password, $status = 'active', $roles = [];
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
        $this->authorize('user-create');
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'staff_id' => 'required|string|max:50|unique:users,staff_id',
            'phone' => 'nullable|string|max:20',
            'joining_date' => 'nullable|date',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'status' => 'required|in:active,inactive,banned',
            'roles' => 'array|exists:roles,name',
        ], [
            'name.required' => 'The name field is required.',
            'staff_id.unique' => 'This staff ID is already taken.',
            'email.unique' => 'This email is already registered.',
            'roles.exists' => 'One or more selected roles are invalid.',
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'staff_id' => $validated['staff_id'],
                'phone' => $validated['phone'],
                'joining_date' => $validated['joining_date'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'status' => $validated['status'],
                'created_by' => Auth::check() ? Auth::id() : null,
            ]);

            $user->assignRole($this->roles);
            $this->resetInputFields();
            $this->dispatch('notify', message: 'User created successfully.', type: 'success');
        } catch (\Exception $e) {
            Log::error('User creation failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to create user.', type: 'error');
        }
    }

    public function edit($id)
    {
        $this->authorize('user-edit');
        $user = User::findOrFail($id);
        $this->editId = $id;
        $this->name = $user->name;
        $this->staff_id = $user->staff_id;
        $this->phone = $user->phone;
        $this->joining_date = $user->joining_date ? $user->joining_date->format('Y-m-d') : null;
        $this->email = $user->email;
        $this->status = $user->status;
        $this->roles = $user->getRoleNames()->toArray();
    }

    public function update()
    {
        $this->authorize('user-edit');
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'staff_id' => 'required|string|max:50|unique:users,staff_id,'.$this->editId,
            'phone' => 'nullable|string|max:20',
            'joining_date' => 'nullable|date',
            'email' => 'required|email|unique:users,email,'.$this->editId,
            'password' => 'nullable|string|min:8',
            'status' => 'required|in:active,inactive,banned',
            'roles' => 'array|exists:roles,name',
        ], [
            'name.required' => 'The name field is required.',
            'staff_id.unique' => 'This staff ID is already taken.',
            'email.unique' => 'This email is already registered.',
            'roles.exists' => 'One or more selected roles are invalid.',
        ]);

        try {
            $user = User::findOrFail($this->editId);
            $user->update([
                'name' => $validated['name'],
                'staff_id' => $validated['staff_id'],
                'phone' => $validated['phone'],
                'joining_date' => $validated['joining_date'],
                'email' => $validated['email'],
                'password' => $this->password ? bcrypt($this->password) : $user->password,
                'status' => $validated['status'],
                'updated_by' => Auth::check() ? Auth::id() : null,
            ]);

            $user->syncRoles($this->roles);
            $this->resetInputFields();
            $this->editId = null;
            $this->dispatch('notify', message: 'User updated successfully.', type: 'success');
        } catch (\Exception $e) {
            Log::error('User update failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to update user.', type: 'error');
        }
    }

    public function delete($id)
    {
        $this->authorize('user-delete');
        try {
            User::findOrFail($id)->delete();
            $this->dispatch('notify', message: 'User deleted successfully.', type: 'success');
        } catch (\Exception $e) {
            Log::error('User deletion failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to delete user.', type: 'error');
        }
    }

    public function bulkDelete()
    {
        $this->authorize('user-delete');
        if (empty($this->selectedUsers)) {
            $this->dispatch('notify', message: 'No users selected for deletion.', type: 'warning');
            return;
        }
        try {
            User::whereIn('id', $this->selectedUsers)->delete();
            $this->selectedUsers = [];
            $this->dispatch('notify', message: 'Selected users deleted successfully.', type: 'success');
        } catch (\Exception $e) {
            Log::error('Bulk user deletion failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to delete selected users.', type: 'error');
        }
    }

    public function restore($id)
    {
        $this->authorize('user-restore');
        try {
            User::withTrashed()->findOrFail($id)->restore();
            $this->dispatch('notify', message: 'User restored successfully.', type: 'success');
        } catch (\Exception $e) {
            Log::error('User restoration failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to restore user.', type: 'error');
        }
    }

    public function bulkAssignRoles()
    {
        $this->authorize('user-edit');
        $this->validate(['roles' => 'required|array|exists:roles,name'], [
            'roles.required' => 'Please select at least one role.',
            'roles.exists' => 'One or more selected roles are invalid.',
        ]);
        try {
            foreach ($this->selectedUsers as $userId) {
                $user = User::findOrFail($userId);
                $user->syncRoles($this->roles);
            }
            $this->selectedUsers = [];
            $this->roles = [];
            $this->dispatch('notify', message: 'Roles assigned successfully.', type: 'success');
        } catch (\Exception $e) {
            Log::error('Bulk role assignment failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to assign roles.', type: 'error');
        }
    }

    public function export($format)
    {
        $this->authorize('user-export');
        $users = $this->getUsersQuery()->get();

        try {
            if ($format === 'pdf') {
                $pdf = Pdf::loadView('exports.users', ['users' => $users]);
                return response()->streamDownload(fn() => print($pdf->output()), 'users.pdf');
            } elseif ($format === 'excel') {
                return Excel::download(new class($users) implements \Maatwebsite\Excel\Concerns\FromCollection {
                    private $users;
                    public function __construct($users) { $this->users = $users; }
                    public function collection() { return $this->users; }
                }, 'users.xlsx');
            }
        } catch (\Exception $e) {
            Log::error('Export failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to export users.', type: 'error');
        }
    }

    private function getUsersQuery()
    {
        $query = $this->showTrashed ? User::withTrashed() : User::query();
        return $query->where('name', 'like', '%'.$this->search.'%')
                     ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
                     ->orderBy($this->sortField, $this->sortDirection)
                     ->with('roles');
    }

    private function resetInputFields()
    {
        $this->name = $this->staff_id = $this->phone = $this->joining_date = $this->email = $this->password = null;
        $this->status = 'active';
        $this->roles = [];
    }

    public function mount()
    {
        $this->roles = Role::all();
    }

    public function render()
    {
        $this->authorize('user-list');
        $users = $this->getUsersQuery()->paginate(10);
        Log::info('Users count: ' . $users->count());
        return view('livewire.user-management', [
            'users' => $users,
            'roles' => $this->roles,
        ]);
    }
}