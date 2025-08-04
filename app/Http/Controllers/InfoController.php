<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;

class InfoController extends Controller
{
    public function getCategories(): JsonResponse
    {
        $categories = Category::all();

        return response()->json(['categories' => $categories], 200);
    }
}
