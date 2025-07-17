<?php

namespace App\Http\Controllers;

use App\Models\EmailVerificationToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
	public function requestVerification(): JsonResponse
	{
		$email = request('email');

		$user = User::where('email', $email)->first();

		if (!$user) {
			return response()->json(['message' => 'User not found'], 404);
		}

		if ($user->email_verified_at) {
			return response()->json(['message' => 'User is Already verified'], 409);
		}

		$user->sendEmailVerificationNotification();

		return response()->json(['message' => 'Verification email sent'], 200);
	}

	public function verify(Request $request): JsonResponse
	{
		$token = request('token');

		$record = EmailVerificationToken::where('token', $token)
			->where('expires_at', '>', Carbon::now())
			->first();

		if (!$record) {
			return response()->json(['message' => 'Invalid token.'], 422);
		}

		$user = User::find($record->user_id);

		if (!$user) {
			return response()->json(['message' => 'User not found.'], 404);
		}

		if ($user->hasVerifiedEmail()) {
			return response()->json(['message' => 'Email already verified.'], 409);
		}

		$user->markEmailAsVerified();
		$record->delete();

		auth('web')->logout();

		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return response()->json(['message' => 'Email verified.'], 200);
	}
}
