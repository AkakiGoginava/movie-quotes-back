<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
{
    protected $simple = false;

    public function __construct($resource, $simple = false)
    {
        parent::__construct($resource);
        $this->simple = $simple;
    }

    public function toArray(Request $request): array
    {
        if ($this->simple) {
            return [
                'id'         => $this->id,
                'text'       => $this->text,
                'poster_url' => $this->poster_url,
            ];
        }

        return [
            'id'         => $this->id,
            'text'       => $this->text,
            'poster_url' => $this->poster_url,
            'movie'      => new MovieResource($this->movie),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public static function simple($resource)
    {
        return collect($resource)->map(function ($item) {
            return new static($item, true);
        });
    }
}
