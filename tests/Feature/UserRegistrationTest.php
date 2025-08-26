<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('User Registration', function () {
    it('registers a user with valid data', function () {
        $response = $this->postJson('/api/register', [
            'name'                  => 'testuser',
            'email'                 => 'testuser@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
        ]);
    });

    it('fails registration with invalid data', function () {
        $response = $this->postJson('/api/register', [
            'name'                  => '',
            'email'                 => 'not-an-email',
            'password'              => 'p',
            'password_confirmation' => 'different',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    });
});
