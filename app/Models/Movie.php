<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Movie extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'director',
        'year',
        'description',
        'user_id',
    ];

    protected $casts = [
        'title'       => 'array',
        'director'    => 'array',
        'description' => 'array',
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

        return asset('images/placeholder.png');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }
}
