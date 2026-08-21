<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $adminRole = Role::create(['name' => 'Admin']);
        $userRole = Role::create(['name' => 'User']);

        activity()->withoutLogs(function () use ($adminRole) {
            // Create default super admin
            $admin = User::create([
                'name' => 'Super Admin',
                'email' => 'admin@srpa.mil.ph',
                'password' => bcrypt('password'),
                'is_active' => true,
            ]);
            
            $admin->assignRole($adminRole);
        });
    }
}
