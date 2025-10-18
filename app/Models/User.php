<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements CanResetPassword, HasMedia, MustVerifyEmail
{
    use HasFactory;
    use InteractsWithMedia;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'email_verified_at',
        'google_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['avatar_url'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function getAvatarUrlAttribute(): string
    {
        $media = $this->getFirstMedia('avatar');

        if ($media) {
            return $media->getUrl();
        }

        return asset('images/default-avatar.jpg');
    }

    protected function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile();
    }

    public function movies(): HasMany
    {
        return $this->hasMany(Movie::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
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

    public function sendPasswordResetNotification($token): void
    {
        $frontendUrl = config('app.frontend_url');

        $url = "{$frontendUrl}?action=reset-password&token=" . $token . '&email=' . $this->email;

        $this->notify(new ResetPasswordNotification($url));
    }
}
