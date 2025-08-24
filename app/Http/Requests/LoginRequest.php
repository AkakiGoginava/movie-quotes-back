<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class LoginRequest extends FormRequest
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
            'password' => ['required'],
            'remember' => ['boolean'],
        ];
    }
}
