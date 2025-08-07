<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text.en'  => ['required', 'string'],
            'text.ka'  => ['required', 'string'],
            'movie_id' => ['required', 'exists:movies,id'],
            'poster'   => ['required', 'file', 'image', 'max:2048'],
        ];
    }
}
