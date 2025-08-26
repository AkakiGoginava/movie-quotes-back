<?php

use App\Events\QuoteLiked;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Support\Facades\Event;

describe('Quote Like Notification', function () {
    it('creates a notification when a quote is liked', function () {
        $user = User::factory()->create();
        $quoteOwner = User::factory()->create();
        $quote = Quote::factory()->create(['user_id' => $quoteOwner->id]);

        $quote->notifyLike($user);

        expect(Notification::where([
            'user_id'         => $quoteOwner->id,
            'from_user_id'    => $user->id,
            'type'            => 'like',
            'notifiable_id'   => $quote->id,
            'notifiable_type' => Quote::class,
        ])->exists())->toBeTrue();
    });

    it('broadcasts event when a quote is liked', function () {
        Event::fake();

        $user = User::factory()->create();
        $quoteOwner = User::factory()->create();
        $quote = Quote::factory()->create(['user_id' => $quoteOwner->id]);

        $quote->notifyLike($user);

        Event::assertDispatched(QuoteLiked::class, function ($event) use ($quote, $user) {
            return $event->quote->id === $quote->id && $event->user->id === $user->id;
        });
    });
});
