<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class RolePermissionRepository
{
    public function getAllRoles(array $filters = [], bool $withTrashed = false): LengthAwarePaginator
    {
        $query = Role::query();

        if ($withTrashed) {
            $query->withTrashed();
        }

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['sort_by'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_direction'] ?? 'asc');
        }

        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function getAllPermissions(array $filters = [], bool $withTrashed = false): LengthAwarePaginator
    {
        $query = Permission::query();

        if ($withTrashed) {
            $query->withTrashed();
        }

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['sort_by'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_direction'] ?? 'asc');
        }

        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function createRole(array $data): Role
    {
        return Role::create($data);
    }

    public function createPermission(array $data): Permission
    {
        return Permission::create($data);
    }

    public function updateRole(Role $role, array $data): Role
    {
        $role->update($data);
        return $role;
    }

    public function updatePermission(Permission $permission, array $data): Permission
    {
        $permission->update($data);
        return $permission;
    }

    public function deleteRole(Role $role): bool
    {
        return $role->delete();
    }

    public function deletePermission(Permission $permission): bool
    {
        return $permission->delete();
    }

    public function restoreRole($id): ?Role
    {
        $role = Role::withTrashed()->findOrFail($id);
        $role->restore();
        return $role;
    }

    public function restorePermission($id): ?Permission
    {
        $permission = Permission::withTrashed()->findOrFail($id);
        $permission->restore();
        return $permission;
    }

    public function getAllRolesForExport(array $filters = []): Collection
    {
        $query = Role::query();

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        return $query->get();
    }

    public function getAllPermissionsForExport(array $filters = []): Collection
    {
        $query = Permission::query();

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        return $query->get();
    }
}