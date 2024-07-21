<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

it('allows a user to register', function () {
    uses(RefreshDatabase::class);
    $response = $this->postJson('/api/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(200);
});

it('allows a user to login', function () {
    uses(RefreshDatabase::class);
    // Create a user in the database
    $user = User::create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => Hash::make('password'),
        'password_confirmation' => Hash::make('password'),
    ]);

    // Attempt to log in with the created user's credentials
    $response = $this->postJson('/api/login', [
        'email' => 'john@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    // Assert the response status and structure
    $response->assertStatus(200)
             ->assertJsonStructure(['token']);
});