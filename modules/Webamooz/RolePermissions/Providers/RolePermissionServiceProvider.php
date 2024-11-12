<?php

namespace Webamooz\RolePermissions\Providers;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Webamooz\RolePermissions\Database\Seeders\RolePermissionTableSeeder;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\RolePermissions\Model\Role;
use Webamooz\RolePermissions\Policies\RolePermissionPolicy;

class RolePermissionServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'RolePermissions');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/role_permissions_route.php');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../Lang');  //translation lang
        DatabaseSeeder::$seeders[] = RolePermissionTableSeeder::class;  //سیدری که ساتختیم میریزه درون آرایه و درون دیتابیس سیدر کال میکنه
//      array_push(DatabaseSeeder::$seeders , RolePermissionTableSeeder::class);

        //این قبل همه پالیسی اجرا میشه + اگر صحیح یا غلط باشد دیگه درون پالیسی های دیگه نمیره مگر حالت نال باش بره سراغ پالیسی + چون درون پرمیژن ی بار بنویسیم در تمامی ماژول اجرا
        Gate::policy(Role::class, RolePermissionPolicy::class);  //Model::class , ModelPolicy::class
        Gate::before(function ($user) {
            return $user->hasPermissionTo(Permission::PERMISSION_SUPER_ADMIN) ? true : null;  //سوپر ادمین نبود برو درون پلیسی ببین دسترسی مدیریت دوره داره یا نه
        });
    }

    public function boot()
    {
        $this->app->booted(function () {
            config()->set('sidebar.items.role-permissions', [
                "icon" => "i-role-permissions",
                "title" => "نقش های کاربر",
                "url" => route('role-permissions.index'),
                "permission" => Permission::PERMISSION_MANAGE_USERS,
            ]);
        });
    }

}
