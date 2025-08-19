<?php

namespace App\Events;

use App\Models\Quote;
use App\Models\QuoteComment;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
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

    public function broadcastOn(): Channel
    {
        return new Channel('quotes');
    }
}
