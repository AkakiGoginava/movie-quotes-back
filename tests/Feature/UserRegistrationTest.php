<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'name'  => 'testuser',
            'email' => 'testuser@example.com',
        ]);

        $user = User::where('email', 'testuser@example.com')->first();
        expect(Hash::check('password', $user->password))->toBeTrue();

        expect(Auth::user()->email)->toBe('testuser@example.com');
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

        $userCountBefore = User::count();

        $this->assertDatabaseCount('users', $userCountBefore);
    });

    it('prevents duplicate email registration', function () {
        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->postJson('/api/register', [
            'name'                  => 'newuser',
            'email'                 => 'existing@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422);

        expect(User::where('email', 'existing@example.com')->count())->toBe(1);
    });

    it('requires password confirmation', function () {
        $response = $this->postJson('/api/register', [
            'name'                  => 'testuser',
            'email'                 => 'testuser@example.com',
            'password'              => 'password',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertStatus(422);

        $userCountBefore = User::count();

        $this->assertDatabaseCount('users', $userCountBefore);
    });

    it('validates required fields', function () {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);

        $userCountBefore = User::count();

        $this->assertDatabaseCount('users', $userCountBefore);
    });

    it('sets email verification timestamp to null by default', function () {
        $response = $this->postJson('/api/register', [
            'name'                  => 'testuser',
            'email'                 => 'testuser@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email'             => 'testuser@example.com',
            'email_verified_at' => null,
        ]);
    });
});
