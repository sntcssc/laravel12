<?php

namespace App\Livewire\Role;

use App\Services\RolePermissionService;
use Livewire\Component;
use App\Models\Role;
use App\Models\Permission;

class EditRole extends Component
{
    public Role $role;
    public $name;
    public $permissions = [];

    protected $rules = [
        'name' => 'required|string|max:255|unique:roles,name,{{role.id}}',
        'permissions' => 'array',
    ];

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->name = $role->name;
        $this->permissions = $role->permissions->pluck('name')->toArray();
    }

    public function render()
    {
        $allPermissions = Permission::all()->pluck('name', 'id');
        return view('livewire.role.edit-role', compact('allPermissions'));
    }

    public function save(RolePermissionService $roleService)
    {
        $this->validate();

        $roleService->updateRole($this->role, [
            'name' => $this->name,
            'permissions' => $this->permissions,
        ]);

        session()->flash('success', 'Role updated successfully.');
        return redirect()->route('roles.index');
    }
}