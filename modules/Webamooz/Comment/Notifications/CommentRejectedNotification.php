<?php

namespace Webamooz\Comment\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kavenegar\LaravelNotification\KavenegarChannel;
use Webamooz\Comment\Models\Comment;

class CommentRejectedNotification extends Notification
{
    use Queueable;

    public Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        $channels = ['mail'];
        $channels[] = 'database';  //بدونه باید درون دیتابیس ذخیره بشه +  متود toArray
//      if (!is_null($notifiable->telegram)) $channels[] = TelegramChannel::class;  //اگر آیدی تلگرام داشت بریز درون آرایه چنل ها
        return $channels;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }


    public function toTelegram($notifiable)
    {
        if (!is_null($notifiable->telegram)) {
            return TelegramMessage::create()
                ->to($notifiable->telegram)  //user id telegram -> 966868056
                ->content("دیدیگاه شما رد شد.")
                ->button('مشاهده دوره', $this->comment->commentable->path());
        }
        return false;
    }


    public function toArray($notifiable)  //save to database -> in column data(json) همه موارد درون ریترن در ستون دیتا که آبجکت ذخیره و بقیه موارد پر میشن
    {
        return [
            "message" => "دیدیگاه شما رد شد",
            "url" => $this->comment->commentable->path(),
        ];
    }

}
