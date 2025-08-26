<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Google Auth', function () {
    it('authenticates with Google successfully', function () {
        $mockGoogleUser = new class
        {
            public function getEmail()
            {
                return 'googleuser@example.com';
            }

            public function getName()
            {
                return 'Google User';
            }

            public function getId()
            {
                return 'google-id-123';
            }
        };

        $mockSocialite = Mockery::mock('overload:Laravel\Socialite\Facades\Socialite');

        $mockSocialite->shouldReceive('driver')->with('google')->andReturnSelf();
        $mockSocialite->shouldReceive('getAccessTokenResponse')->andReturn(['access_token' => 'mock-token']);
        $mockSocialite->shouldReceive('userFromToken')->with('mock-token')->andReturn($mockGoogleUser);

        $response = $this->postJson('/api/google', [
            'code' => 'mock-code',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['user' => ['id', 'email', 'name', 'google_id', 'email_verified_at']]);

        $this->assertDatabaseHas('users', [
            'email'     => 'googleuser@example.com',
            'google_id' => 'google-id-123',
        ]);
    });
});
