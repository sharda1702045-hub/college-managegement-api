<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed permissions/roles before tests
        $this->seed();
    }

    public function test_registration_route_is_disabled(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(404);
    }

    public function test_admin_user_can_login(): void
    {
        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
        ]);
        $user->assignRole('Admin');

        $response = $this->postJson('/api/login', [
            'email' => 'jane@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User logged in successfully',
            ])
            ->assertJsonStructure([
                'data' => ['access_token', 'token_type'],
            ]);
    }

    public function test_non_admin_user_cannot_login(): void
    {
        $user = User::create([
            'name' => 'Staff User',
            'email' => 'staff_test@example.com',
            'password' => Hash::make('password123'),
        ]);
        $user->assignRole('Staff');

        $response = $this->postJson('/api/login', [
            'email' => 'staff_test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => [
                    'email' => ['Access denied: only administrators are allowed to access the API.']
                ]
            ]);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
            ]);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User logged out successfully',
            ]);

        $this->assertCount(0, $user->tokens);
    }
}
