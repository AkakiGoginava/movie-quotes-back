<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailNotification extends Notification
{
	use Queueable;

	protected $token;

	public function __construct($token)
	{
		$this->token = $token;
	}

	public function via(object $notifiable): array
	{
		return ['mail'];
	}

	public function toMail(object $notifiable): MailMessage
	{
		$verificationUrl = $this->verificationUrl($notifiable);
		$name = $notifiable->name;
		$content = 'Thanks for joining Movie quotes! We really appreciate it. Please click the button below to verify your account:';
		$linkName = 'Verify account';

		return (new MailMessage)
			->subject('Please verify your email')
			->markdown('email.index', [
				'url'      => $verificationUrl,
				'name'     => $name,
				'content'  => $content,
				'linkName' => $linkName,
			]);
	}

	public function verificationUrl(object $notifiable): string
	{
		$frontendUrl = env('FRONTEND_APP_URL');

		return "{$frontendUrl}?action=verify&token={$this->token}";
	}
}
