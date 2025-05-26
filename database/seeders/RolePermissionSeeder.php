<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $editorRole = Role::create(['name' => 'editor']);
        $viewerRole = Role::create(['name' => 'viewer']);

        // Create permissions
        $permissions = [
            'view-users', 'create-users', 'edit-users', 'delete-users',
            'view-roles', 'create-roles', 'edit-roles', 'delete-roles',
            'view-permissions', 'create-permissions', 'edit-permissions', 'delete-permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole->syncPermissions($permissions);
        $editorRole->syncPermissions(['view-users', 'create-users', 'edit-users', 'view-roles']);
        $viewerRole->syncPermissions(['view-users', 'view-roles']);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'staff_id' => 'STAFF001',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
            'status' => 'active',
            'created_by' => null,
            'updated_by' => null,
        ]);
        $admin->assignRole('admin');
    }
}
