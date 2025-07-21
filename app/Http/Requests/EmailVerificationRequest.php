<?php

namespace App\Http\Requests;

use App\Rules\EmailVerified;
use Illuminate\Foundation\Http\FormRequest;

class EmailVerificationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'exists:users,email',
                new EmailVerified,
            ],
        ];
    }
}
