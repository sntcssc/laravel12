<?php

namespace App\Services;

use App\Repositories\RolePermissionRepository;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

class RolePermissionService
{
    protected $repository;

    public function __construct(RolePermissionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllRoles(array $filters = [], bool $withTrashed = false)
    {
        return $this->repository->getAllRoles($filters, $withTrashed);
    }

    public function getAllPermissions(array $filters = [], bool $withTrashed = false)
    {
        return $this->repository->getAllPermissions($filters, $withTrashed);
    }

    public function createRole(array $data): Role
    {
        $role = $this->repository->createRole($data);
        if (!empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }
        return $role;
    }

    public function createPermission(array $data): Permission
    {
        return $this->repository->createPermission($data);
    }

    public function updateRole(Role $role, array $data): Role
    {
        $role = $this->repository->updateRole($role, $data);
        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }
        return $role;
    }

    public function updatePermission(Permission $permission, array $data): Permission
    {
        return $this->repository->updatePermission($permission, $data);
    }

    public function deleteRole(Role $role): bool
    {
        return $this->repository->deleteRole($role);
    }

    public function deletePermission(Permission $permission): bool
    {
        return $this->repository->deletePermission($permission);
    }

    public function bulkDeleteRoles(array $roleIds): void
    {
        foreach ($roleIds as $roleId) {
            $role = Role::findOrFail($roleId);
            $this->deleteRole($role);
        }
    }

    public function bulkDeletePermissions(array $permissionIds): void
    {
        foreach ($permissionIds as $permissionId) {
            $permission = Permission::findOrFail($permissionId);
            $this->deletePermission($permission);
        }
    }

    public function restoreRole($id): ?Role
    {
        return $this->repository->restoreRole($id);
    }

    public function restorePermission($id): ?Permission
    {
        return $this->repository->restorePermission($id);
    }

    public function exportRoles(array $filters = []): Collection
    {
        return $this->repository->getAllRolesForExport($filters);
    }

    public function exportPermissions(array $filters = []): Collection
    {
        return $this->repository->getAllPermissionsForExport($filters);
    }
}