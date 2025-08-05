<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'    => ['sometimes', 'array'],
            'title.en' => ['sometimes', 'string', 'max:255'],
            'title.ka' => ['sometimes', 'string', 'max:255'],

            'director'    => ['sometimes', 'array'],
            'director.en' => ['sometimes', 'string', 'max:255'],
            'director.ka' => ['sometimes', 'string', 'max:255'],

            'description'    => ['sometimes', 'array'],
            'description.en' => ['sometimes', 'string'],
            'description.ka' => ['sometimes', 'string'],

            'categories'   => ['sometimes', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],

            'year'   => ['sometimes', 'string', 'size:4', 'regex:/^\d{4}$/'],
            'poster' => ['sometimes', 'file', 'image', 'max:2048'],
        ];
    }
}
