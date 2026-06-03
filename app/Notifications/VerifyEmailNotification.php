<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends VerifyEmail
{
    protected function verificationUrl($notifiable): string
    {
        $id = $notifiable->getKey();

        $hash = sha1(
            $notifiable->getEmailForVerification()
        );

        $temporarySignedUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $id,
                'hash' => $hash,
            ]
        );

        parse_str(
            parse_url(
                $temporarySignedUrl,
                PHP_URL_QUERY
            ),
            $query
        );

        return config('app.frontend_url')
            . '/verify-email'
            . '?id=' . urlencode($id)
            . '&hash=' . urlencode($hash)
            . '&expires=' . urlencode($query['expires'])
            . '&signature=' . urlencode($query['signature']);
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verify Email Address')
            ->line('Please verify your email.')
            ->action(
                'Verify Email',
                $this->verificationUrl($notifiable)
            );
    }
}
