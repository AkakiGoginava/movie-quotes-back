<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyMovieRequest;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MovieController extends Controller
{
    public function index(): JsonResponse
    {
        $perPage = 9;

        $query = Movie::with(['categories', 'user'])
            ->where('user_id', Auth::id())
            ->latest('id');

        $totalMovies = Movie::where('user_id', Auth::id())->count();

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

    public function show(Movie $movie): JsonResponse
    {
        $movie->load(['categories', 'user', 'quotes']);

        $movieResource = new MovieResource($movie);

        return response()->json([
            'movie' => $movieResource,
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
            'movie'   => (new MovieResource($movie->load(['categories', 'user']))),
        ], 201);
    }

    public function update(UpdateMovieRequest $request, Movie $movie): JsonResponse
    {
        $attributes = $request->validated();

        $movie->title = $attributes['title'];
        $movie->director = $attributes['director'];
        $movie->description = $attributes['description'];
        $movie->year = $attributes['year'];

        $movie->save();

        $movie->categories()->sync($attributes['categories']);

        if ($request->hasFile('poster')) {
            $movie->clearMediaCollection('poster');
            $movie->addMediaFromRequest('poster')->toMediaCollection('poster');
        }

        return response()->json([
            'message' => 'Movie updated successfully',
            'movie'   => (new MovieResource($movie->load(['categories', 'user']))),
        ], 200);
    }

    public function destroy(DestroyMovieRequest $request, Movie $movie): JsonResponse
    {
        $movie->delete();

        return response()->json(['message' => 'Movie deleted successfully'], 200);
    }
}
