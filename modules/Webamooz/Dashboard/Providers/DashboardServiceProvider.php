<?php

namespace Webamooz\Dashboard\Providers;

use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/dashboard_route.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views/', 'Dashboard');
        $this->mergeConfigFrom(__DIR__ . '/../Config/sidebar.php', 'sidebar');  //config('sidebar'); دسترسی به کانفیگ با صدا زدن کلید
    }

    public function boot()
    {
        $this->app->booted(function () {
            config()->set('sidebar.items.dashboard', [
                "icon" => "i-dashboard",
                "title" => "پیشخوان",
                "url" => route('home')
            ]);
        });
    }

}
