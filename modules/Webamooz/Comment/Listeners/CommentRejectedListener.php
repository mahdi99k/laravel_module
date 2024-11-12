<?php

namespace Webamooz\Comment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Webamooz\Comment\Notifications\CommentApprovedNotification;
use Webamooz\Comment\Notifications\CommentRejectedNotification;
use Webamooz\Comment\Notifications\CommentSubmittedNotification;

class CommentRejectedListener
{

    public function __construct()
    {
        //
    }

    public function handle($event)  //با رد دیدیگاه برای کاربری که دیدیگاه گذاشته
    {
        //notification for comment owner -> تایید دیدیگاه
        //$event->comment->user -> رفتن نوتیفیکیشن به کابری که دیدیگاه گذاشته پس از تایید دیدیگاه
        $event->comment->user->notify(new CommentRejectedNotification($event->comment));  //event->comment->comment صاحب دیدیگاه
    }

}
