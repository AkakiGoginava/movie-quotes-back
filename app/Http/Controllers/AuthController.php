<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUserRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $user = User::create($credentials);

        Auth::login($user);

        event(new Registered($user));

        return response()->json(['user' => $user], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $attributes = $request->validated();

        $credentials = Arr::only($attributes, ['email', 'password']);
        $remember = $attributes['remember'] ?? false;

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return response()->json(['message' => 'Login successful'], 201);
        }

        return response()->json(['errors' => [
            'email'    => ['Invalid credentials'],
            'password' => ['Invalid credentials']],
        ], 422);
    }

    public function googleAuth(Request $request): JsonResponse
    {
        $code = request('code');

        if (! $code) {
            return response()->json([
                'message' => 'Authorization code is invalid',
            ], 400);
        }

        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name'              => $googleUser->getName(),
                    'google_id'         => $googleUser->getId(),
                    'email_verified_at' => now(),
                ]
            );

            Auth::login($user);
            $request->session()->regenerate();

            return response()->json(['user' => $user], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Authentication failed',
            ], 400);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        auth('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully'], 201);
    }

    public function getUser(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Could not get user'], 401);
        }

        return response()->json(['user' => $user]);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $attributes = $request->validated();
        $email = $attributes['email'];

        $status = Password::sendResetLink(['email' => $email]);

        if ($status === Password::ResetLinkSent) {
            return response()->json(['message' => 'Link sent.'], 200);
        }

        return response()->json(['message' => 'Could not send link.'], 401);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $attributes = $request->validated();

        $status = Password::reset(
            $attributes,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)], 200);
        }

        return response()->json(['message' => __($status)], 422);
    }
}
