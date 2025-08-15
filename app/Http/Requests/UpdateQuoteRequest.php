<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        $quote = $this->route('quote');

        return $quote && $quote->user_id === Auth::id();
    }

    public function rules(): array
    {
        return [
            'text.en'  => ['present', 'required', 'string'],
            'text.ka'  => ['present', 'required', 'string'],
            'movie_id' => ['present', 'required', 'exists:movies,id'],
            'poster'   => ['sometimes', 'file', 'image', 'max:2048'],
        ];
    }
}
