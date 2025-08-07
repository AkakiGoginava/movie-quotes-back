<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'text'       => $this->text,
            'poster_url' => $this->poster_url,
            'movie'      => new MovieResource($this->movie),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
