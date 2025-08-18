<?php

namespace App\Http\Controllers;

use App\Events\QuoteCommented;
use App\Events\QuoteLiked;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Notification;
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
        $user = Auth::user();

        $existingLike = QuoteLike::where([
            'user_id'  => $userId,
            'quote_id' => $quote->id,
        ])->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
            $message = 'Quote unliked successfully';
            
            Notification::where([
                'user_id' => $quote->user_id,
                'from_user_id' => $userId,
                'type' => 'like',
                'notifiable_id' => $quote->id,
                'notifiable_type' => Quote::class,
            ])->delete();
        } else {
            QuoteLike::create([
                'user_id'  => $userId,
                'quote_id' => $quote->id,
            ]);
            $liked = true;
            $message = 'Quote liked successfully';
            
            if ($quote->user_id !== $userId) {
                Notification::create([
                    'user_id' => $quote->user_id,
                    'from_user_id' => $userId,
                    'type' => 'like',
                    'notifiable_id' => $quote->id,
                    'notifiable_type' => Quote::class,
                ]);
                
                broadcast(new QuoteLiked($quote, $user));
            }
        }

        return response()->json([
            'message'     => $message,
            'liked'       => $liked,
            'likes_count' => $quote->likesCount(),
        ]);
    }

    public function addComment(StoreCommentRequest $request, Quote $quote): JsonResponse
    {
        $userId = Auth::id();
        $user = Auth::user();
        
        $comment = QuoteComment::create([
            'user_id'  => $userId,
            'quote_id' => $quote->id,
            'content'  => $request->content,
        ]);

        $comment->load('user');

        if ($quote->user_id !== $userId) {
            Notification::create([
                'user_id' => $quote->user_id,
                'from_user_id' => $userId,
                'type' => 'comment',
                'notifiable_id' => $quote->id,
                'notifiable_type' => Quote::class,
            ]);
            
            broadcast(new QuoteCommented($quote, $comment, $user));
        }

        return response()->json([
            'message' => 'Comment added successfully',
            'comment' => new CommentResource($comment),
        ], 201);
    }
}
