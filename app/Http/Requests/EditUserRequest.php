<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
{
    public function rules(): array
    {
        $lowercaseAndNumbers = '/^[a-z0-9]+$/';

        return [
            'name'     => ['sometimes', 'min:3', 'max:15', 'unique:users', "regex:{$lowercaseAndNumbers}"],
            'password' => ['sometimes', 'confirmed', 'min:8', 'max:15', "regex:{$lowercaseAndNumbers}"],
            'image'    => ['sometimes', 'file'],
        ];
    }
}
