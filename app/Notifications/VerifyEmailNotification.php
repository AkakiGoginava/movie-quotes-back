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
        $content = __('mail.verify_content');
        $linkName = __('mail.verify_link_name');

        return (new MailMessage)
            ->subject(__('mail.verify_subject'))
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

        return "{$frontendUrl}?action=verify&token={$this->token}&email={$notifiable->email}";
    }
}
