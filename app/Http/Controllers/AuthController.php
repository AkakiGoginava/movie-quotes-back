<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
	public function register(RegisterRequest $request): JsonResponse
	{
		$attributes = $request->validated();

		$user = User::create($attributes);

		Auth::login($user);

		return response()->json(['user' => $user], 201);
	}
}
