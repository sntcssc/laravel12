<?php

namespace App\Livewire\Permission;

use App\Services\RolePermissionService;
use Livewire\Component;
use App\Models\Permission;

class EditPermission extends Component
{
    public Permission $permission;
    public $name;

    protected $rules = [
        'name' => 'required|string|max:255|unique:permissions,name,{{permission.id}}',
    ];

    public function mount(Permission $permission)
    {
        $this->permission = $permission;
        $this->name = $permission->name;
    }

    public function render()
    {
        return view('livewire.permission.edit-permission');
    }

    public function save(RolePermissionService $permissionService)
    {
        $this->validate();

        $permissionService->updatePermission($this->permission, [
            'name' => $this->name,
        ]);

        session()->flash('success', 'Permission updated successfully.');
        return redirect()->route('permissions.index');
    }
}