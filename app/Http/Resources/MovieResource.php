<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    protected $language;

    public function toArray(Request $request): array
    {
        $language = $request->header('Language', 'en');

        return [
            'id'          => $this->id,
            'title'       => $this->title[$language] ?? '',
            'director'    => $this->director[$language] ?? '',
            'description' => $this->description[$language] ?? '',
            'year'        => $this->year,
            'poster_url'  => $this->poster_url,
            'categories'  => $this->categories,
            'user'        => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
            ],
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }

    public function toFullArray(): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'director'    => $this->director,
            'description' => $this->description,
            'year'        => $this->year,
            'poster_url'  => $this->poster_url,
            'categories'  => $this->categories,
            'user'        => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
            ],
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
