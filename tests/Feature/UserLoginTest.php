<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

describe('User Login', function () {
    it('logs in with valid credentials', function () {
        $user = User::factory()->create([
            'email'             => 'loginuser@example.com',
            'password'          => 'password',
            'email_verified_at' => now(),
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'loginuser@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(201);

        expect(Auth::user()->email)->toBe('loginuser@example.com');
    });

    it('fails login with invalid credentials', function () {
        User::factory()->create([
            'email'    => 'validuser@example.com',
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'validuser@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422);
    });

    it('validates required fields', function () {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);
    });

    it('logs out successfully', function () {
        $user = User::factory()->create([
            'email'    => 'logoutuser@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(201);
    });

    it('cannot logout when not authenticated', function () {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    });
});
