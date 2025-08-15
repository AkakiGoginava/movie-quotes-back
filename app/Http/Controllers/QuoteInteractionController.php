<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Quote;
use App\Models\QuoteComment;
use App\Models\QuoteLike;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class QuoteInteractionController extends Controller
{
    public function toggleLike(Quote $quote): JsonResponse
    {
        $userId = Auth::id();

        $existingLike = QuoteLike::where([
            'user_id'  => $userId,
            'quote_id' => $quote->id,
        ])->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
            $message = 'Quote unliked successfully';
        } else {
            QuoteLike::create([
                'user_id'  => $userId,
                'quote_id' => $quote->id,
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
        $comment = QuoteComment::create([
            'user_id'  => Auth::id(),
            'quote_id' => $quote->id,
            'content'  => $request->content,
        ]);

        $comment->load('user');

        return response()->json([
            'message' => 'Comment added successfully',
            'comment' => new CommentResource($comment),
        ], 201);
    }
}
