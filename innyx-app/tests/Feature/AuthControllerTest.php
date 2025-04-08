<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_with_valid_data()
    {
        $role = Role::factory()->create();
        $response = $this->postJson('/api/register', [
            'name' => 'Lucas Dev',
            'email' => 'lucas@innyx.dev',
            'password' => 'senha123',
            'role_id' => $role->id
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email', 'role_id']]);

        $this->assertDatabaseHas('users', ['email' => 'lucas@innyx.dev']);
    }

    public function test_register_with_invalid_data()
    {
        $role = Role::factory()->create();

        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'invalid',
            'password' => '1',
            'role_id' => $role->id
        ]);

        $response->assertStatus(422);
    }

    public function test_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'lucas@login.com',
            'password' => bcrypt('senha123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'lucas@login.com',
            'password' => 'senha123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email']]);
    }

    public function test_login_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'lucas@teste.com',
            'password' => bcrypt('senha123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'lucas@teste.com',
            'password' => 'senhaerrada',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Credenciais inválidas']);
    }
}

