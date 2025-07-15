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
		$title = 'Verify your email address to get started';
		$content = "Hi {$notifiable->name},\n\nYou're almost there! To complete the sign up, please verify your email address.";
		$linkName = 'Verify now';

		return (new MailMessage)
			->subject('Please verify your email')
			->markdown('email.index', [
				'url'      => $verificationUrl,
				'title'    => $title,
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
