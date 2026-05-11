<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_user_cannot_access_user_list()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('usuarios.index'));

        $response->assertStatus(403);
    }

    public function test_admin_user_can_access_user_list()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('usuarios.index'));

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_create_user_via_api()
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/users', [
            'name' => 'New User',
            'email' => 'new@user.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'user'
        ]);

        $response->assertStatus(403);
    }
}
