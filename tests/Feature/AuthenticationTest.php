<?php

namespace Tests\Feature;

use App\Models\User;
use App\Livewire\Auth\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirects_unauthenticated_users_to_login()
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_renders_the_login_page()
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_authenticates_users_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@srpa.mil.ph',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);

        Livewire::test(Login::class)
            ->set('email', 'test@srpa.mil.ph')
            ->set('password', 'password123')
            ->call('login')
            ->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
    }

    public function test_fails_authentication_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'test2@srpa.mil.ph',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);

        Livewire::test(Login::class)
            ->set('email', 'test2@srpa.mil.ph')
            ->set('password', 'wrongpassword')
            ->call('login')
            ->assertHasErrors('email');

        $this->assertGuest();
    }
}
