<?php

namespace Webamooz\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Webamooz\User\Mail\VerifyCodeMail;
use Webamooz\User\Services\VerifyCodeService;

class VerifyMailNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
//      return ['mail' , 'sms' , 'telegram'];
        return ['mail'];
    }

    public function toMail($notifiable)  //$notifiable -> auth()->user();
    {
        $code = VerifyCodeService::generateRandomCode();
        VerifyCodeService::setCache($notifiable->id, $code , now()->addDay());  //$notifiable -> auth()->user();
        return (new VerifyCodeMail($notifiable->name, $code))->to($notifiable->email);
    }

    /*public function toSms()  //هم نام via که میسازیم نحوه اطلاع رسانی باید متود ساخته بشه to+name
    {

    }

    public function toTelegram()
    {

    }*/

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
