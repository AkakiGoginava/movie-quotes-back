<?php

namespace App\Http\Requests;

use App\Models\EmailVerificationToken;
use App\Models\User;
use App\Rules\EmailVerified;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class VerifyTokenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'token' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $record = EmailVerificationToken::where('token', $value)
                        ->where('expires_at', '>', Carbon::now())
                        ->first();

                    if (! $record) {
                        $fail('Invalid or expired token.');

                        return;
                    }

                    $user = User::find($record->user_id);

                    if (! $user) {
                        $fail('User not found.');

                        return;
                    }

                    $emailVerifiedRule = new EmailVerified;
                    $emailVerifiedRule->validate('email', $user->email, $fail);
                },
            ],
        ];
    }
}
