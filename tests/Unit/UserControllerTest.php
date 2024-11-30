<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\UserFactory;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginSuccess()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    public function testLoginFailure()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
        
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
    }

    public function testCreateUser()
    {
        
        $response = $this->postJson('/api/create/user', [
            'name' => Factory::create()->name(),
            'email' => 'newuser@example.com',
            'password' => 'password',
            'isAdmin' => true
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'email']);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'isAdmin' => true,
        ]);
    }

    public function testUpdateUser()
    {
        
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'isAdmin' => false,
        ]);

        
        $response = $this->actingAs($user)->putJson('/api/update/user', [
            'id' => $user->id,
            'name' => Factory::create()->name(),
            'email' => 'updated@example.com',
            'password' => 'new-password',
            'isAdmin' => true,
        ]);

        
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'email']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'updated@example.com',
            'isAdmin' => true,
        ]);
    }

    public function testDuplicateEmailUser()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'isAdmin' => false,
        ]);

        $userToUpdate = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'isAdmin' => true,
        ]);

        
        $response = $this->actingAs($userToUpdate)->putJson('/api/update/user', [
            'id' => $userToUpdate->id,
            'name' => Factory::create()->name(),
            'email' => $user->email,
            'password' => 'new-password',
            'isAdmin' => true,
        ]);

        
        $response->assertStatus(409);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'test@example.com',
            'isAdmin' => false,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $userToUpdate->id,
            'email' => 'admin@example.com',
            'isAdmin' => true,
        ]);
    }
}
