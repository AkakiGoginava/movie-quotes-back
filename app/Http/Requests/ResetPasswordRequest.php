<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class ResetPasswordRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $locale = $this->header('Language', 'en');
        App::setLocale($locale);
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'token'    => ['required'],
            'password' => ['required', 'confirmed'],
        ];
    }
}
