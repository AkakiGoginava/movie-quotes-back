<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuoteSeeder extends Seeder
{
    public function run(): void
    {
        if (Movie::count() === 0) {
            $this->call(MovieSeeder::class);
        }

        if (User::count() === 0) {
            User::factory()->count(5)->create();
        }

        $movies = Movie::all();
        $users = User::all();

        foreach ($movies as $movie) {
            $quoteCount = fake()->numberBetween(1, 3);
            
            Quote::factory()
                ->count($quoteCount)
                ->forMovie($movie)
                ->forUser($users->random())
                ->create();
        }
    }
}
