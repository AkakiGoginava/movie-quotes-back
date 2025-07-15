<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
		$credentials = $request->validated();

		if (Auth::attempt($credentials)) {
			$request->session()->regenerate();

			return response()->json(['message' => 'Login successful'], 200);
		}

		return response()->json(['errors' => ['email' => ['Invalid credentials']]], 422);
	}

	public function logout(Request $request): JsonResponse
	{
		$user = $request->user();

		auth('web')->logout();

		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return response()->json(['message' => 'Logged out successfully'], 201);
	}

	public function getUser(Request $request): JsonResponse
	{
		$user = $request->user();

		if (!$user) {
			return response()->json(['message' => 'Could not get user'], 401);
		}

		return response()->json(['user' => $user]);
	}
}
