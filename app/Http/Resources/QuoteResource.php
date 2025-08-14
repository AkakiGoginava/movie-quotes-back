<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class QuoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'text'           => $this->text,
            'poster_url'     => $this->poster_url,
            'movie_id'       => $this->movie->id,
            'movie_title'    => $this->movie->title,
            'movie_year'     => $this->movie->year,
            'likes_count'    => $this->likesCount(),
            'is_liked'      => $this->isLikedBy(Auth::user()),
            'comments_count' => $this->commentsCount(),
            'comments'       => CommentResource::collection($this->comments),
        ];
    }
}
