<?php

namespace Webamooz\Comment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Webamooz\Comment\Notifications\CommentApprovedNotification;
use Webamooz\Comment\Notifications\CommentSubmittedNotification;

class CommentApprovedListener
{

    public function __construct()
    {
        //
    }

    public function handle($event)  //با تایید دیدیگاه یا پاسخ یک نوتیفیکیشن برای مدرس میاد + یکی برای کاربری که دیدیگاه گذاشته
    {
        //notification for teacher -> یک کامنت جدید(کاربر) + یک پاسخ به دیدگاه خودش(کاربر)
        if ($event->comment->user_id != $event->comment->commentable->teacher->id) {  //اگر کسی که کامنت گذاشته آیدی ش مخالف با مدرس دوره برای خود مدرس نوتیفیکیشن بره
            //$event->comment->(relationCourse)->(relationUser)
            $event->comment->commentable->teacher->notify(new CommentSubmittedNotification($event->comment));
        }

        //notification for comment owner -> تایید دیدیگاه
        //$event->comment->user -> رفتن نوتیفیکیشن به کابری که دیدیگاه گذاشته پس از تایید دیدیگاه
        $event->comment->user->notify(new CommentApprovedNotification($event->comment));  //event->comment->comment صاحب دیدیگاه
    }

}
