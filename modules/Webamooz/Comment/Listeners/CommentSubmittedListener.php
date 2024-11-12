<?php

namespace Webamooz\Comment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Webamooz\Comment\Notifications\CommentSubmittedNotification;

class CommentSubmittedListener
{

    public function __construct()
    {
        //
    }

    public function handle($event)  //اگر مدرس یا ادمین یا ی کاربر ناشناس پاسخ بده دیدیگاه کاربر نوتیفیکیشن برای صاحب دیدگاه
    {
        //notification for comment owner -> پاسخ دیدگاه کاربر
        //$event->comment->comment->user);  $event->comment->comment -> بیا کامنتی که بای پاسخ گذاشته بگیر و کسی که صاحب اون دیدگاه بوده اطلاعاتش بده و بعدش آیدی صاحب دیدگاه
        //اگر اون کامنت پاسخ بود$ event->comment->comment_id + مدرس یا ادمین یا کاربر ناشناس سایت پاسخ داد نه باز صاحب دیدگاه پاسخ بده، حالا نوتیفیکیشن برای کسی که کامنت گذاشته بره
        if ($event->comment->comment_id && $event->comment->user_id != $event->comment->comment->user->id) {
            $event->comment->comment->user->notify(new CommentSubmittedNotification($event->comment));  //event->comment->comment صاحب دیدیگاه
        }
    }

}
