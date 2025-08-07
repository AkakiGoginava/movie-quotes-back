<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteFactory extends Factory
{
    protected $model = Quote::class;

    public function definition(): array
    {
        return [
            'text' => [
                'en' => $this->faker->sentence($this->faker->numberBetween(3, 8)),
                'ka' => $this->faker->sentence($this->faker->numberBetween(3, 8)),
            ],
            'movie_id' => Movie::factory(),
            'user_id' => User::factory(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Quote $quote) {
            $this->addPlaceholderPoster($quote);
        });
    }

    private function addPlaceholderPoster(Quote $quote): void
    {
        $width = 400;
        $height = 300;
        $imageUrl = "https://picsum.photos/{$width}/{$height}?random=quote" . $quote->id;
        
        $quote->addMediaFromUrl($imageUrl)
            ->usingName('Quote Poster')
            ->usingFileName('quote_poster_' . $quote->id . '.jpg')
            ->toMediaCollection('posters');
    }
}
