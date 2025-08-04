<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    public function index(): JsonResponse
    {
        $movies = Movie::with(['categories', 'user'])
            ->get()
            ->map(function ($movie) {
                return [
                    'id'          => $movie->id,
                    'title'       => $movie->title['en'] ?? '',
                    'director'    => $movie->director['en'] ?? '',
                    'description' => $movie->description['en'] ?? '',
                    'year'        => $movie->year,
                    'poster_url'  => $movie->poster_url,
                    'categories'  => $movie->categories,
                    'user'        => [
                        'id'   => $movie->user->id,
                        'name' => $movie->user->name,
                    ],
                    'created_at'  => $movie->created_at,
                    'updated_at'  => $movie->updated_at,
                ];
            });

        return response()->json(['movies' => $movies], 200);
    }

    public function show($id): JsonResponse
    {
        $movie = Movie::with(['categories', 'user'])->find($id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        $movieData = [
            'id'          => $movie->id,
            'title'       => $movie->title['en'] ?? '',
            'director'    => $movie->director['en'] ?? '',
            'description' => $movie->description['en'] ?? '',
            'year'        => $movie->year,
            'poster_url'  => $movie->poster_url,
            'categories'  => $movie->categories,
            'user'        => [
                'id'   => $movie->user->id,
                'name' => $movie->user->name,
            ],
            'created_at'  => $movie->created_at,
            'updated_at'  => $movie->updated_at,
        ];
 
        return response()->json(['movie' => $movieData], 200);
    }

    public function store(StoreMovieRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $movie = Movie::create([
            'title'       => $validated['name'],
            'director'    => $validated['director'],
            'year'        => $validated['year'],
            'description' => $validated['description'],
            'user_id'     => Auth::id(),
        ]);

        $movie->categories()->attach($validated['categories']);

        if ($request->hasFile('poster')) {
            $movie->addMediaFromRequest('poster')->toMediaCollection('poster');
        }

        return response()->json([
            'message' => 'Movie created successfully',
            'movie'   => $movie->load(['categories', 'user']),
        ], 201);
    }
}
