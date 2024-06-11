<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;

uses(DatabaseTransactions::class);

it('allows a user to register', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(200);
});

it('allows a user to login', function () {
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