<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\VerifyEmailNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements CanResetPassword, MustVerifyEmail
{
	use HasFactory;

	use Notifiable;

	protected $fillable = [
		'name',
		'email',
		'password',
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected function casts(): array
	{
		return [
			'email_verified_at' => 'datetime',
			'password'          => 'hashed',
		];
	}

	public function sendEmailVerificationNotification(): void
	{
		$token = Str::random(64);

		EmailVerificationToken::create([
			'user_id'    => $this->id,
			'token'      => $token,
			'expires_at' => Carbon::now()->addMinutes(120),
		]);

		$this->notify(new VerifyEmailNotification($token));
	}
}
