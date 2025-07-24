<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
{
    public function rules(): array
    {
        $lowercaseAndNumbers = '/^[a-z0-9]+$/';

        return [
            'name' => ['min:3', 'max:15', 'unique:users', "regex:{$lowercaseAndNumbers}"],
            'password' => ['confirmed', 'min:8', 'max:15', "regex:{$lowercaseAndNumbers}"],
            'image' => ['file']
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->filled('name') && !$this->filled('password') && !$this->hasFile('image')) {
                $validator->errors()->add('fields', 'At least one field is required.');
            }
        });
    }
}
