<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
    public function store(StoreMovieRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $movie = Movie::create([
            'title' => $validated['title'],
            'director' => $validated['director'],
            'year' => $validated['year'],
            'description' => $validated['description'],
        ]);

        $movie->categories()->attach($validated['categories']);

        if ($request->hasFile('image')) {
            $movie->addMediaFromRequest('image')->toMediaCollection('poster');
        }

        return response()->json([
            'message' => 'Movie created successfully',
            'movie' => $movie->load('categories')
        ], 201);
    }
}
