<?php

namespace Webamooz\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Webamooz\User\Mail\ResetPasswordRequestMail;
use Webamooz\User\Mail\VerifyCodeMail;
use Webamooz\User\Services\VerifyCodeService;

class ResetPasswordRequestNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)  //$notifiable -> auth()->user();
    {
        $code = VerifyCodeService::generateRandomCode();
        VerifyCodeService::setCache($notifiable->id, $code , 120);  //$notifiable -> auth()->user();
        return (new ResetPasswordRequestMail($notifiable->name, $code))->to($notifiable->email);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
