<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Auth;
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
        $login = $attributes['email'];
        $password = $attributes['password'];
        $remember = $attributes['remember'] ?? false;

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        $credentials = [$field => $login, 'password' => $password];

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return response()->json(['message' => __('Login successful')], 201);
        }

        $errorMessage = __('auth.failed');

        return response()->json(['errors' => [
            'email'    => [$errorMessage],
            'password' => [$errorMessage],
        ]], 422);
    }

    public function googleAuth(Request $request): JsonResponse
    {
        $code = $request->input('code');

        if (! $code) {
            return response()->json(['message' => 'Authorization code is required'], 400);
        }

        try {
            $tokenResponse = Socialite::driver('google')->getAccessTokenResponse($code);
            $googleUser = Socialite::driver('google')->userFromToken($tokenResponse['access_token']);

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
            return response()->json(['message' => 'Authentication failed: ' . $e->getMessage()], 400);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        auth('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully'], 201);
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
                    'password' => $password,
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
