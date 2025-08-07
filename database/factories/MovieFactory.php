<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovieFactory extends Factory
{
    protected $model = Movie::class;

    public function definition(): array
    {
        return [
            'title' => [
                'en' => $this->faker->sentence(3, false),
                'ka' => $this->faker->sentence(3, false),
            ],
            'director' => [
                'en' => $this->faker->name(),
                'ka' => $this->faker->name(),
            ],
            'description' => [
                'en' => $this->faker->paragraph(),
                'ka' => $this->faker->paragraph(),
            ],
            'year' => $this->faker->numberBetween(1950, 2024),
            'user_id' => User::factory(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Movie $movie) {
            $categoryIds = Category::pluck('id')->toArray();
            if (!empty($categoryIds)) {
                $randomCategories = $this->faker->randomElements($categoryIds, $this->faker->numberBetween(1, 3));
                $movie->categories()->attach($randomCategories);
            }

            $this->addPlaceholderPoster($movie);
        });
    }

    private function addPlaceholderPoster(Movie $movie): void
    {
        $width = 400;
        $height = 600;
        $imageUrl = "https://picsum.photos/{$width}/{$height}?random=" . $movie->id;
        
        $movie->addMediaFromUrl($imageUrl)
            ->usingName('Movie Poster')
            ->usingFileName('poster_' . $movie->id . '.jpg')
            ->toMediaCollection('posters');
    }
}
