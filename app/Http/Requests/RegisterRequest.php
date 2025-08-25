<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        $lowercaseAndNumbers = '/^[a-z0-9]+$/';

        return [
            'name'     => ['required', 'min:3', 'max:15', 'unique:users', "regex:{$lowercaseAndNumbers}"],
            'email'    => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8', 'max:15', "regex:{$lowercaseAndNumbers}"],
        ];
    }
}
