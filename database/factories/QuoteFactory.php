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
            'user_id'  => User::factory(),
        ];
    }

    public function forMovie(Movie $movie): static
    {
        return $this->state(fn (array $attributes) => [
            'movie_id' => $movie->id,
        ]);
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}
