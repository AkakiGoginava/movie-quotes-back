<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateMovieRequest extends FormRequest
{
    public function authorize(): bool
    {
        $movie = $this->route('movie');

        return $movie && $movie->user_id === Auth::id();
    }

    public function rules(): array
    {
        return [
            'title'    => ['present', 'array'],
            'title.en' => ['present', 'string', 'max:255'],
            'title.ka' => ['present', 'string', 'max:255'],

            'director'    => ['present', 'array'],
            'director.en' => ['present', 'string', 'max:255'],
            'director.ka' => ['present', 'string', 'max:255'],

            'description'    => ['present', 'array'],
            'description.en' => ['present', 'string'],
            'description.ka' => ['present', 'string'],

            'categories'   => ['present', 'array', 'min:1'],
            'categories.*' => ['integer', 'exists:categories,id'],

            'year'   => ['present', 'string', 'size:4', 'regex:/^\d{4}$/'],
            'poster' => ['sometimes', 'file', 'image', 'max:2048'],
        ];
    }
}
