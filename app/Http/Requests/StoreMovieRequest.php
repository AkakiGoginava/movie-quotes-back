<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'    => ['required', 'array'],
            'title.en' => ['required', 'string', 'max:255'],
            'title.ka' => ['required', 'string', 'max:255'],

            'director'    => ['required', 'array'],
            'director.en' => ['required', 'string', 'max:255'],
            'director.ka' => ['required', 'string', 'max:255'],

            'description'    => ['required', 'array'],
            'description.en' => ['required', 'string'],
            'description.ka' => ['required', 'string'],

            'categories'   => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],

            'year'  => ['required', 'string', 'size:4', 'regex:/^\d{4}$/'],
            'poster' => ['required', 'file', 'image', 'max:2048'],
        ];
    }
}
