<?php

namespace Webamooz\Common\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CommonServiceProvider extends ServiceProvider
{

    private $namespace = "Webamooz\Common\Http\Controllers";

    public function register()
    {
        Route::middleware(['web'])->namespace($this->namespace)->group(__DIR__ . '/../Routes/common_routes.php');
//      $this->loadRoutesFrom(__DIR__ .'/../Routes/common_routes.php');
        $this->loadViewsFrom(__DIR__ . "/../Resources/Views/", 'Common');
    }

    public function boot()
    {
//      return __DIR__ . "/../helpers.php";  //better use composer.json inside module common
        view()->composer('Dashboard::layouts.header', function ($view) {  //روی صفحه ای خاص اطلاعات میفرستیم
            //readNotifications -> تایم خورده read_at اونایی که ستون
            $notifications = auth()->user()->unreadNotifications;  //unreadNotifications -> میره اونایی که ستون read_at خالی میاره
            return $view->with(compact('notifications'));  //$view->with -> برو تو این صفحه و همراه خودت ببر این متغیر
        });
    }

}
