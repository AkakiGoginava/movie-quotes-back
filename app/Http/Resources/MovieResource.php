<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'director'     => $this->director,
            'description'  => $this->description,
            'year'         => $this->year,
            'poster_url'   => $this->poster_url,
            'categories'   => $this->categories,
            'quotes_count' => $this->quotes()->count(),
            'user'         => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
            ],
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
