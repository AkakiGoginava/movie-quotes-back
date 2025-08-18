<?php

namespace App\Events;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuoteLiked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Quote $quote,
        public User $user
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.' . $this->quote->user_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'quote.liked';
    }

    public function broadcastWith(): array
    {
        $user = $this->user;

        return [
            'quote_id' => $this->quote->id,
            'quote_text' => $this->quote->text,
            'liker' => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar_url' => $user->getAvatarUrlAttribute(),
            ],
            'message' => 'Reacted to your quote',
            'timestamp' => now()->toISOString(),
        ];
    }
}
