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

    public function movies(Request $request): JsonResponse
    {
        $user = Auth::user();

        $perPage = 9;

        $totalMovies = $user->movies()->count();

        $movies = $user->movies()
            ->latest('id') 
            ->cursorPaginate($perPage)
            ->through(function ($movie) {
                return [
                    'id'          => $movie->id,
                    'title'       => $movie->title['en'] ?? '',
                    'director'    => $movie->director['en'] ?? '',
                    'description' => $movie->description['en'] ?? '',
                    'year'        => $movie->year,
                    'poster_url'  => $movie->poster_url,
                    'created_at'  => $movie->created_at,
                    'updated_at'  => $movie->updated_at,
                ];
            });

        $response = $movies->toArray();
        $response['total_movies'] = $totalMovies;

        return response()->json($response, 200);
    }
}
