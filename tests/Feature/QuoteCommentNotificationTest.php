<?php

use App\Events\QuoteCommented;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\QuoteComment;
use App\Models\User;
use Illuminate\Support\Facades\Event;

describe('Quote Comment Notification', function () {
    it('creates a notification when a quote is commented', function () {
        $user = User::factory()->create();
        $quoteOwner = User::factory()->create();
        $quote = Quote::factory()->create(['user_id' => $quoteOwner->id]);

        $comment = QuoteComment::create([
            'user_id'  => $user->id,
            'quote_id' => $quote->id,
            'content'  => 'comment',
        ]);
        $quote->notifyComment($user, $comment);

        expect(Notification::where([
            'user_id'         => $quoteOwner->id,
            'from_user_id'    => $user->id,
            'type'            => 'comment',
            'notifiable_id'   => $quote->id,
            'notifiable_type' => Quote::class,
        ])->exists())->toBeTrue();
    });

    it('broadcasts event when a quote is commented', function () {
        Event::fake();

        $user = User::factory()->create();
        $quoteOwner = User::factory()->create();
        $quote = Quote::factory()->create(['user_id' => $quoteOwner->id]);
        $comment = QuoteComment::create([
            'user_id'  => $user->id,
            'quote_id' => $quote->id,
            'content'  => 'Nice quote!',
        ]);

        $quote->notifyComment($user, $comment);

        Event::assertDispatched(QuoteCommented::class, function ($event) use ($quote, $user, $comment) {
            return $event->quote->id === $quote->id &&
                   $event->user->id === $user->id &&
                   $event->comment->id === $comment->id;
        });
    });
});
