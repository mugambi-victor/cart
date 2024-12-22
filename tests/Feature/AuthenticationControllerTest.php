<?php

use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

it('registers a new user successfully with Faker', function () {
    $faker = Faker::create();

    $data = [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => 'password123',
        'role' => 'customer',
    ];

    $response = $this->postJson('/api/register', $data);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'user' => ['id', 'name', 'email', 'role', 'created_at', 'updated_at'],
            'token',
        ]);
});

it('logs in a user successfully', function () {
    $faker = Faker::create();

    // Create a user with Faker
    $email = $faker->unique()->safeEmail;
    $password = 'password123';

    \App\Models\User::factory()->create([
        'email' => $email,
        'password' => Hash::make($password),
    ]);

    $data = [
        'email' => $email,
        'password' => $password,
    ];

    $response = $this->postJson('/api/login', $data);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'user' => ['id', 'name', 'email', 'role', 'created_at', 'updated_at'],
            'token',
        ]);
});

it('logs out a user successfully', function () {
    $user = \App\Models\User::factory()->create();
    $token = $user->createToken('user')->plainTextToken;

    $response = $this->postJson('/api/logout', [], [
        'Authorization' => "Bearer $token",
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Successfully logged out.',
        ]);
});

it('fails registration when all fields are not provided', function () {
    $response = $this->postJson('/api/register', []); // No data provided

    $response->assertStatus(422)
        ->assertJsonStructure([
            'errors' => [
                'name',
                'email',
                'password',
            ],
        ]);
});

it('succeeds when all fields are provided correctly', function () {
    $faker = Faker::create();

    $data = [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => 'password123',
        'role' => 'customer',
    ];

    $response = $this->postJson('/api/register', $data);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'user' => ['id', 'name', 'email', 'role', 'created_at', 'updated_at'],
            'token',
        ]);
});



it('fails login with overly long email and password', function () {
    $faker = Faker::create();

    $data = [
        'email' => $faker->text(300), // Exceeding typical email length
        'password' => str_repeat('a', 500), // Excessively long password
    ];

    $response = $this->postJson('/api/login', $data);

    $response->assertStatus(422)
        ->assertJsonStructure([
            'errors' => [
                'email',
                'password',
            ],
        ]);
});