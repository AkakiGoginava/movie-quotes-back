<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
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
