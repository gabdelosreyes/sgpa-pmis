<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Remove old generalized permissions if they exist
        Permission::whereIn('name', ['manage personnel', 'manage users', 'manage roles'])->delete();

        $permissions = [
            'view users', 'add users', 'edit users', 'delete users',
            'view personnel', 'add personnel', 'edit personnel', 'delete personnel',
            'view roles', 'add roles', 'edit roles', 'delete roles',
            'view activity logs'
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }
        // Automatically assign all permissions to Admin
        $allPermissions = Permission::all();
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions($allPermissions);
    }
}
