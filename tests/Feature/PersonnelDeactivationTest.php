<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Personnel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PersonnelDeactivationTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $deletePersonnel = Permission::firstOrCreate(['name' => 'delete personnel']);
        $adminRole->givePermissionTo($deletePersonnel);
        
        $this->admin = User::factory()->create(['is_active' => true]);
        $this->admin->assignRole($adminRole);
    }

    public function test_allows_authorized_user_to_deactivate_personnel()
    {
        $this->actingAs($this->admin);

        $personnel = Personnel::factory()->create([
            'is_active' => true,
        ]);

        Livewire::test(\App\Livewire\Personnel\Index::class)
            ->call('deletePersonnel', $personnel->id)
            ->assertDispatched('pg:eventRefresh-personnel-table');

        $this->assertFalse((bool) $personnel->fresh()->is_active);
    }
}
