<?php

namespace Webamooz\User\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\User\Http\Middleware\StoreUserIp;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Webamooz\User\database\Seeders\UsersTableSeeder;
use Webamooz\User\Models\User;
use Webamooz\User\Policies\UserPolicy;

class UserServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/user_routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');
        Factory::guessFactoryNamesUsing(function ($modelName) {  //name factory
            return "Webamooz\User\database\\factories\\" . class_basename($modelName) . 'Factory';  //class_basename + Factory -> 'namespace'.UserFactory
        });
        $this->loadFactoriesFrom(__DIR__ . '/../database/factories/');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views/', 'User');  //1)path view  2)namespace User::Front/register.blade.php
        $this->loadJsonTranslationsFrom(__DIR__ . "/../Lang/");

        config()->set('auth.providers.users.model', User::class);  //1)key(path inside config)  2)value
        Gate::policy(User::class, UserPolicy::class);
        DatabaseSeeder::$seeders[] = UsersTableSeeder::class;  //سیدری که ساتختیم میریزه درون آرایه و درون دیتابیس سیدر کال میکنه
//      array_push(DatabaseSeeder::$seeders , RolePermissionTableSeeder::class);
    }

    public function boot()
    {
        $this->app['router']->pushMiddlewareToGroup('web', StoreUserIp::class);  //حالت عادی -> app/kernel -> $middlewareGroups -> web

        config()->set('sidebar.items.users', [
            "icon" => "i-users",
            "title" => "کاربران",
            "url" => route('users.index'),
            "permission" => Permission::PERMISSION_MANAGE_USERS
        ]);

        config()->set('sidebar.items.userInformation', [
            "icon" => "i-users",
            "title" => "اطلاعات کاربری",
            "url" => route('users.profile'),
        ]);
    }

}
