<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DestroyMovieRequest extends FormRequest
{
    public function authorize(): bool
    {
        $movie = $this->route('movie');

        return $movie && $movie->user_id === Auth::id();
    }
}
