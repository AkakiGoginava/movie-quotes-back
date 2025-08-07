<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Quote extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'text',
        'movie_id',
    ];

    protected $casts = [
        'text' => 'array',
    ];

    protected $appends = ['poster_url'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('poster')
            ->singleFile();
    }

    public function getPosterUrlAttribute()
    {
        $media = $this->getFirstMedia('poster');

        if ($media) {
            return $media->getUrl();
        }

        return null;
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}
