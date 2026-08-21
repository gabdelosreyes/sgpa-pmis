<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class RoleEnforcementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $basicUser;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $userRole = Role::firstOrCreate(['name' => 'User']);
        
        $viewUsers = Permission::firstOrCreate(['name' => 'view users']);
        $viewRoles = Permission::firstOrCreate(['name' => 'view roles']);
        
        $adminRole->givePermissionTo([$viewUsers, $viewRoles]);
        
        $this->admin = User::factory()->create(['is_active' => true]);
        $this->admin->assignRole($adminRole);
        
        $this->basicUser = User::factory()->create(['is_active' => true]);
        $this->basicUser->assignRole($userRole);
    }

    public function test_allows_admin_to_access_protected_routes()
    {
        $this->actingAs($this->admin);
        
        $this->get('/users')->assertStatus(200);
        $this->get('/roles-permissions')->assertStatus(200);
    }

    public function test_forbids_basic_user_from_accessing_protected_routes_without_permissions()
    {
        $this->actingAs($this->basicUser);
        
        $this->get('/users')->assertStatus(403);
        $this->get('/roles-permissions')->assertStatus(403);
    }
}
