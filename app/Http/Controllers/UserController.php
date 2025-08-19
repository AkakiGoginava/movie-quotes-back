<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUser(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Could not get user'], 401);
        }

        return response()->json(['user' => $user]);
    }

    public function update(EditUserRequest $request): JsonResponse
    {
        $attributes = $request->validated();

        $user = Auth::user();

        if ($request->filled('name')) {
            $user->name = $attributes['name'];
        }

        if ($request->filled('password')) {
            $user->password = $attributes;
        }

        if ($request->hasFile('image')) {
            $user->addMediaFromRequest('image')->toMediaCollection('avatar');
        }

        $user->save();

        return response()->json(['message' => 'Edited successfully'], 200);
    }
}
