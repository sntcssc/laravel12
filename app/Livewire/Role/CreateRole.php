<?php

namespace App\Livewire\Role;

use App\Services\RolePermissionService;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class CreateRole extends Component
{
    public $name;
    public $permissions = [];

    protected $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
        'permissions' => 'array',
    ];

    public function render()
    {
        $allPermissions = Permission::all()->pluck('name', 'id');
        return view('livewire.role.create-role', compact('allPermissions'));
    }

    public function save(RolePermissionService $roleService)
    {
        $this->validate();

        $roleService->createRole([
            'name' => $this->name,
            'permissions' => $this->permissions,
        ]);

        session()->flash('message', 'Role created successfully.');
        return redirect()->route('roles.index');
    }
}