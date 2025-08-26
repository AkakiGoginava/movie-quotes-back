<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('User Login', function () {
    it('logs in with valid credentials', function () {
        $user = User::factory()->create([
            'email'    => 'loginuser@example.com',
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'loginuser@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['message']);
    });

    it('fails login with invalid credentials', function () {
        $response = $this->postJson('/api/login', [
            'email'    => 'wronguser@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    });

    it('logs out successfully', function () {
        $user = User::factory()->create([
            'email'    => 'logoutuser@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(201);
        $response->assertJson(['message' => 'Logged out successfully']);
    });
});
