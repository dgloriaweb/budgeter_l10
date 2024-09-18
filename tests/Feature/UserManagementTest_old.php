<?php

use App\Models\User;

use Illuminate\Support\Facades\Hash;


it('allows a user to register', function () {
    $this->markTestSkipped('Skipping this test for now.');
    $response = $this->postJson('/api/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(200);
});

it('allows a user to login', function () {
    $this->markTestSkipped('Skipping this test for now.');
    // Create a user in the database
    $password = Hash::make('password');
    $user = User::create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' =>$password,
        'password_confirmation' => $password,
    ]);

    // Attempt to log in with the created user's credentials
    $response = $this->postJson('/api/login',[
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => "password",
        'password_confirmation' => "password",
    ]);

    // Assert the response status and structure
    $response->assertStatus(200)
             ->assertJsonStructure(['token']);
});