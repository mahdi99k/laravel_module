<?php

namespace Webamooz\User\Providers;

use Illuminate\Support\ServiceProvider;
use Webamooz\User\Models\User;

class UserServiceProvider extends ServiceProvider
{

    public function register()
    {
        config()->set('auth.providers.users.model'  , User::class);  //1)key(path inside config)  2)value
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/user_routes.php');   //path route in modules (__DIR__ route currently)
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');  //path Migrations in modules (__DIR__ migrations currently)
        $this->loadFactoriesFrom(__DIR__ . '/../Database/factories');    //path factories in modules (__DIR__ factories currently)
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views' , 'User');  //1)path view  2)namespace User::Front/register.blade.php
    }

}
