<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Quote;
use App\Models\QuoteInteraction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class QuoteInteractionController extends Controller
{
    public function toggleLike(Quote $quote): JsonResponse
    {
        $userId = Auth::id();

        $existingLike = QuoteInteraction::where([
            'user_id'  => $userId,
            'quote_id' => $quote->id,
            'type'     => 'like',
        ])->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
            $message = 'Quote unliked successfully';
        } else {
            QuoteInteraction::create([
                'user_id'  => $userId,
                'quote_id' => $quote->id,
                'type'     => 'like',
                'content'  => null,
            ]);
            $liked = true;
            $message = 'Quote liked successfully';
        }

        return response()->json([
            'message'     => $message,
            'liked'       => $liked,
            'likes_count' => $quote->likesCount(),
        ]);
    }

    public function addComment(StoreCommentRequest $request, Quote $quote): JsonResponse
    {
        $comment = QuoteInteraction::create([
            'user_id'  => Auth::id(),
            'quote_id' => $quote->id,
            'type'     => 'comment',
            'content'  => $request->content,
        ]);

        $comment->load('user');

        return response()->json([
            'message' => 'Comment added successfully',
        ], 201);
    }
}
