<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyQuoteRequest;
use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class QuoteController extends Controller
{
    public function index(): JsonResponse
    {
        $perPage = 9;

        $query = Quote::with(['movie', 'comments.user'])
            ->latest('id');

        $totalQuotes = Quote::count();

        $quotes = QueryBuilder::for($query)
            ->allowedFilters([
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->search($value);
                }),
            ])
            ->cursorPaginate($perPage)
            ->through(function ($quote) {
                return new QuoteResource($quote);
            });

        $response = $quotes->toArray();
        $response['total_quotes'] = $totalQuotes;

        return response()->json($response, 200);
    }

    public function store(StoreQuoteRequest $request): JsonResponse
    {
        $quote = Quote::create([
            'text'     => $request->text,
            'movie_id' => $request->movie_id,
            'user_id'  => Auth::id(),
        ]);

        $quote->addMediaFromRequest('poster')
            ->toMediaCollection('posters');

        return response()->json(
            new QuoteResource($quote->load('movie')),
            201
        );
    }

    public function update(UpdateQuoteRequest $request, Quote $quote): JsonResponse
    {
        $quote->update($request->only(['text', 'movie_id']));

        if ($request->hasFile('poster')) {
            $quote->clearMediaCollection('posters');
            $quote->addMediaFromRequest('poster')
                ->toMediaCollection('posters');
        }

        return response()->json(
            new QuoteResource($quote->load('movie'))
        );
    }

    public function destroy(DestroyQuoteRequest $request, Quote $quote): JsonResponse
    {
        $quote->delete();

        return response()->json(['message' => 'Quote deleted successfully'], 200);
    }
}
