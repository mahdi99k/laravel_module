<?php

namespace Webamooz\Course\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Webamooz\Course\Listeners\RegisterUserInTheCourse;
use Webamooz\Payment\Events\PaymentWasSuccessful;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        PaymentWasSuccessful::class => [  //event -> connect event to listeners in all project
            RegisterUserInTheCourse::class,  //listener
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
