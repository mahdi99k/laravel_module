<?php

namespace Webamooz\Comment\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kavenegar\LaravelNotification\KavenegarChannel;
use NotificationChannels\Telegram\TelegramMessage;
use Webamooz\Comment\Mail\CommentSubmittedMail;
use Webamooz\Comment\Models\Comment;

class CommentSubmittedNotification extends Notification
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
//      if (!is_null($notifiable->mobile)) $channels[] = KavenegarChannel::class;
        return $channels;
    }


    public function toMail($notifiable)  //$notifiable -> access user چون در لیستنر نوشتیم که این کامنت روابطش با دوره و روابط دوره با کاربر یا مدرس دوره گرفتیم
    {
        return (new CommentSubmittedMail($this->comment))->to($notifiable->email);  //->to برای ارسال به ایمیل کاربر
    }

    public function toSMS($notifiable)
    {
        return "یک دیدگاه جدید برای دوره ی شما در وب آموز ارسال شده است. جهت مشاهده و ارسال پاسخ روی لینک زیر کلیک نمایید." . '\n' . route('comments.index');
    }

    public function toTelegram($notifiable)
    {
        if (!is_null($notifiable->telegram)) {
            return TelegramMessage::create()
                ->to($notifiable->telegram)  //user id telegram -> 966868056
                ->content("یک دیدگاه جدید برای شما در وب آموز ارسال شده است")
                ->button('مشاهده دوره', $this->comment->commentable->path())
                ->button('مدیریت دیدگاه ها', route('comments.index'));
        }
        return false;
    }

    public function toArray($notifiable)  //save to database -> in column data(json) همه موارد درون ریترن در ستون دیتا که آبجکت ذخیره و بقیه موارد پر میشن
    {
        return [
            "message" => "دیدگاه جدید برای دوره شما ثبت شده است",
            "url" => route('comments.index'),
        ];
    }

}
