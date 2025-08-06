<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = config('categories.default_categories');

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
