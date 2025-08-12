<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteInteraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quote_id',
        'type',
        'content',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function scopeLikes($query)
    {
        return $query->where('type', 'like');
    }

    public function scopeComments($query)
    {
        return $query->where('type', 'comment');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForQuote($query, $quoteId)
    {
        return $query->where('quote_id', $quoteId);
    }

    public function isLike(): bool
    {
        return $this->type === 'like';
    }

    public function isComment(): bool
    {
        return $this->type === 'comment';
    }
}
