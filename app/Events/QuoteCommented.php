<?php

namespace App\Events;

use App\Models\Quote;
use App\Models\QuoteComment;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuoteCommented implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Quote $quote,
        public QuoteComment $comment,
        public User $user
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->quote->user_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'quote.commented';
    }

    public function broadcastWith(): array
    {
        return [
            'quote_id' => $this->quote->id,
            'quote_text' => $this->quote->text,
            'comment' => [
                'id' => $this->comment->id,
                'content' => $this->comment->content,
                'created_at' => $this->comment->created_at,
            ],
            'commenter' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar_url' => $this->user->avatar_url,
            ],
            'message' => 'Commented on your quote',
            'timestamp' => now()->toISOString(),
        ];
    }
}
