<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MovieController extends Controller
{
    public function index(): JsonResponse
    {
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
            ->through(function ($movie) {
                return new MovieResource($movie);
            });

        $response = $movies->toArray();
        $response['total_movies'] = $totalMovies;

        return response()->json($response, 200);
    }

    public function show(Request $request, $id): JsonResponse
    {
        $movie = Movie::with(['categories', 'user'])->find($id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        $movieResource = new MovieResource($movie);
        
        return response()->json([
            'movie' => $movieResource->toFullArray($request)
        ], 200);
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
            'movie'   => (new MovieResource($movie->load(['categories', 'user'])))->toFullArray($request),
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

        $attributes = $request->validated();

        if (isset($attributes['title'])) {
            $movie->title = $attributes['title'];
        }

        if (isset($attributes['director'])) {
            $movie->director = $attributes['director'];
        }

        if (isset($attributes['description'])) {
            $movie->description = $attributes['description'];
        }

        if (isset($attributes['year'])) {
            $movie->year = $attributes['year'];
        }

        $movie->save();

        if (isset($attributes['categories'])) {
            $movie->categories()->sync($attributes['categories']);
        }

        if ($request->hasFile('poster')) {
            $movie->clearMediaCollection('poster');
            $movie->addMediaFromRequest('poster')->toMediaCollection('poster');
        }

        return response()->json([
            'message' => 'Movie updated successfully',
            'movie'   => (new MovieResource($movie->load(['categories', 'user'])))->toFullArray($request),
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
