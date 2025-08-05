<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Movie extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'director', 
        'year',
        'description',
        'user_id',
    ];

    protected $casts = [
        'title' => 'json',
        'director' => 'json', 
        'description' => 'json',
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

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
