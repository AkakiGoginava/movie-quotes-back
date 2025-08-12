<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Quote extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'text',
        'movie_id',
        'user_id',
    ];

    protected $casts = [
        'text' => 'array',
    ];

    protected $appends = ['poster_url'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('posters')
            ->singleFile();
    }

    public function getPosterUrlAttribute()
    {
        $media = $this->getFirstMedia('posters');

        if ($media) {
            return $media->getUrl();
        }

        return asset('images/placeholder.png');
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(QuoteInteraction::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(QuoteInteraction::class)->where('type', 'like');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(QuoteInteraction::class)->where('type', 'comment');
    }

    public function likesCount(): int
    {
        return $this->likes()->count();
    }

    public function commentsCount(): int
    {
        return $this->comments()->count();
    }

    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
