<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MovieController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $language = $request->header('Language', 'en');
        
        $perPage = 9;

        $query = Movie::with(['categories', 'user'])->latest('id');

        $totalMovies = Movie::count();

        $movies = QueryBuilder::for($query)
            ->allowedFilters([
                AllowedFilter::callback('title', function ($query, $value) {
                    $query->where(function ($q) use ($value) {
                        $q->whereJsonContains('title->en', $value)
                          ->orWhereJsonContains('title->ka', $value);
                    });
                }),
            ])
            ->cursorPaginate($perPage)
            ->through(function ($movie) use ($language) {
                return [
                    'id'          => $movie->id,
                    'title'       => $movie->title[$language] ?? '',
                    'director'    => $movie->director[$language] ?? '',
                    'description' => $movie->description[$language] ?? '',
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

        $response = $movies->toArray();
        $response['total_movies'] = $totalMovies;

        return response()->json($response, 200);
    }

    public function show($id): JsonResponse
    {
        $movie = Movie::with(['categories', 'user'])->find($id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        $movieData = [
            'id'          => $movie->id,
            'title'       => $movie->title,
            'director'    => $movie->director,
            'description' => $movie->description,
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
            'title'       => $validated['title'],
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

    public function update(UpdateMovieRequest $request, $id): JsonResponse
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        if ($movie->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized to update this movie'], 403);
        }

        $validated = $request->validated();

        // Update fields
        if (isset($validated['title'])) {
            $movie->title = $validated['title'];
        }

        if (isset($validated['director'])) {
            $movie->director = $validated['director'];
        }

        if (isset($validated['description'])) {
            $movie->description = $validated['description'];
        }

        if (isset($validated['year'])) {
            $movie->year = $validated['year'];
        }

        $movie->save();

        if (isset($validated['categories'])) {
            $movie->categories()->sync($validated['categories']);
        }

        if ($request->hasFile('poster')) {
            $movie->clearMediaCollection('poster');
            $movie->addMediaFromRequest('poster')->toMediaCollection('poster');
        }

        return response()->json([
            'message' => 'Movie updated successfully',
            'movie'   => $movie->load(['categories', 'user']),
        ], 200);
    }

    public function destroy($id): JsonResponse
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        if ($movie->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized to delete this movie'], 403);
        }

        $movie->delete();

        return response()->json(['message' => 'Movie deleted successfully'], 200);
    }
}
