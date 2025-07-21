<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailVerificationRequest;
use App\Http\Requests\VerifyTokenRequest;
use App\Models\EmailVerificationToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class VerificationController extends Controller
{
    public function requestVerification(EmailVerificationRequest $request): JsonResponse
    {
        $email = $request->validated()['email'];

        $user = User::where('email', $email)->first();

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email sent'], 200);
    }

    public function verify(VerifyTokenRequest $request): JsonResponse
    {
        $token = $request->validated()['token'];

        $record = EmailVerificationToken::where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        $user = User::find($record->user_id);

        $user->markEmailAsVerified();
        $record->delete();

        auth('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Email verified.'], 200);
    }
}
