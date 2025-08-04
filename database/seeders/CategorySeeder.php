<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Action',
            'Comedy',
            'Drama',
            'Horror',
            'Romance',
            'Thriller',
            'Sci-Fi',
            'Fantasy',
            'Adventure',
            'Crime',
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
