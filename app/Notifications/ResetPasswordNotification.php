<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
	use Queueable;

	protected $url;

	public function __construct($url)
	{
		$this->url = $url;
	}

	public function via(object $notifiable): array
	{
		return ['mail'];
	}

	public function toMail(object $notifiable): MailMessage
	{
		$name = $notifiable->name;
		$content = 'Please click the button below to reset your password:';
		$linkName = 'Reset password';

		return (new MailMessage)
			->subject('Password reset')
			->markdown('email.index', [
				'url'      => $this->url,
				'name'     => $name,
				'content'  => $content,
				'linkName' => $linkName,
			]);
	}
}
