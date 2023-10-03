<?php

namespace Database\Seeders;

use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create default permissions
        $permissions = [
            ['id' => 1, 'name' => 'create-role', 'guard_name' => 'sanctum'],
            ['id' => 2, 'name' => 'update-role', 'guard_name' => 'sanctum'],
            ['id' => 3, 'name' => 'view-role', 'guard_name' => 'sanctum'],
            ['id' => 4, 'name' => 'delete-role', 'guard_name' => 'sanctum'],
            ['id' => 5, 'name' => 'create-permission', 'guard_name' => 'sanctum'],
            ['id' => 6, 'name' => 'update-permission', 'guard_name' => 'sanctum'],
            ['id' => 7, 'name' => 'view-permission', 'guard_name' => 'sanctum'],
            ['id' => 8, 'name' => 'delete-permission', 'guard_name' => 'sanctum'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // create default roles
        $roles = [
            ['id' => 1, 'name' => 'manage-roles', 'guard_name' => 'sanctum'],
            ['id' => 2, 'name' => 'manage-permissions', 'guard_name' => 'sanctum'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // assign permissions to roles
        $roleManager = Role::find(1);
        $rolePermissions = Permission::whereIn('id', [1,2,3,4])->get();

        $roleManager->syncPermissions($rolePermissions);

        $permissionManager = Role::find(2);
        $permissionPermissions = Permission::whereIn('id', [5,6,7,8])->get();

        $permissionManager->syncPermissions($permissionPermissions);

        // assign to admin user
        $admin = User::find(1);

        $admin->assignRole($roleManager);
        $admin->assignRole($permissionManager);
    }
}
