<?php

namespace Webamooz\Comment\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Webamooz\Comment\Events\CommentApprovedEvent;
use Webamooz\Comment\Events\CommentRejectedEvent;
use Webamooz\Comment\Events\CommentSubmittedEvent;
use Webamooz\Comment\Listeners\CommentApprovedListener;
use Webamooz\Comment\Listeners\CommentRejectedListener;
use Webamooz\Comment\Listeners\CommentSubmittedListener;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        CommentSubmittedEvent::class => [  //event -> ارسال دیدیگاه
            CommentSubmittedListener::class,  //listener
        ],

        CommentApprovedEvent::class => [
            CommentApprovedListener::class
        ],

        CommentRejectedEvent::class => [
            CommentRejectedListener::class
        ]
    ];

    public function boot()
    {
        //
    }

    public function shouldDiscoverEvents()
    {
        return false;
    }
}
