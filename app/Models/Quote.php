<?php

namespace App\Models;

use App\Events\QuoteCommented;
use App\Events\QuoteLiked;
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

    public function likes(): HasMany
    {
        return $this->hasMany(QuoteLike::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(QuoteComment::class)->latest();
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

    public function scopeSearchText($query, $value)
    {
        return $query->where(function ($q) use ($value) {
            $q->where('text->en', 'LIKE', "%{$value}%")
                ->orWhere('text->ka', 'LIKE', "%{$value}%");
        });
    }

    public function scopeSearchMovieTitle($query, $value)
    {
        return $query->whereHas('movie', function ($movieQuery) use ($value) {
            $movieQuery->where(function ($mq) use ($value) {
                $mq->where('title->en', 'LIKE', "%{$value}%")
                    ->orWhere('title->ka', 'LIKE', "%{$value}%");
            });
        });
    }

    public function scopeSearchAll($query, $value)
    {
        return $query->where(function ($q) use ($value) {
            $q->searchText($value)
                ->orWhere(function ($subQuery) use ($value) {
                    $subQuery->searchMovieTitle($value);
                });
        });
    }

    public function scopeSearch($query, $value)
    {
        if (str_starts_with($value, '@')) {
            $searchTerm = substr($value, 1);

            return $query->searchMovieTitle($searchTerm);
        } elseif (str_starts_with($value, '#')) {
            $searchTerm = substr($value, 1);

            return $query->searchText($searchTerm);
        } else {
            return $query->searchAll($value);
        }
    }

    public function notifyLike(User $liker): void
    {
        if ($this->user_id !== $liker->id) {
            Notification::create([
                'user_id'         => $this->user_id,
                'from_user_id'    => $liker->id,
                'type'            => 'like',
                'notifiable_id'   => $this->id,
                'notifiable_type' => self::class,
            ]);

            broadcast(new QuoteLiked($this, $liker));
        }
    }

    public function notifyComment(User $commenter, QuoteComment $comment): void
    {
        if ($this->user_id !== $commenter->id) {
            Notification::create([
                'user_id'         => $this->user_id,
                'from_user_id'    => $commenter->id,
                'type'            => 'comment',
                'notifiable_id'   => $this->id,
                'notifiable_type' => self::class,
            ]);

            broadcast(new QuoteCommented($this, $comment, $commenter));
        }
    }
}
