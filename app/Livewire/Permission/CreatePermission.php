<?php

namespace App\Livewire\Permission;

use App\Services\RolePermissionService;
use Livewire\Component;

class CreatePermission extends Component
{
    public $name;

    protected $rules = [
        'name' => 'required|string|max:255|unique:permissions,name',
    ];

    public function render()
    {
        return view('livewire.permission.create-permission');
    }

    public function save(RolePermissionService $permissionService)
    {
        $this->validate();

        $permissionService->createPermission([
            'name' => $this->name,
        ]);

        session()->flash('message', 'Permission created successfully.');
        return redirect()->route('permissions.index');
    }
}