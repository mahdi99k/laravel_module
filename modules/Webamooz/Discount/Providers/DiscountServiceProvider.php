<?php

namespace Webamooz\Discount\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webamooz\Discount\Models\Discount;
use Webamooz\Discount\Policies\DiscountPolicy;
use Webamooz\RolePermissions\Model\Permission;

class DiscountServiceProvider extends ServiceProvider
{

    private string $namespace = "Webamooz\Discount\Http\Controllers";

    public function register()
    {
        $this->app->register(EventServiceProvider::class);  //ریجستر بکنه ایونت سرویس پروایدر به این قسمت در پروژه
        Route::middleware(['web'])->namespace($this->namespace)->group(__DIR__ . '/../Routes/discount_routes.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views/', 'Discounts');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../Lang/');  //file ترجمه کلممات به صورت جیسون
        Gate::policy(Discount::class, DiscountPolicy::class);  //Model::class , ModelPolicy::class
    }

    public function boot()
    {
        config()->set('sidebar.items.discounts', [
            "icon" => "i-discounts",
            "title" => "تخفیف ها",
            "url" => route('discounts.index'),
            "permissions" => Permission::PERMISSION_MANAGE_DISCOUNT,
        ]);
    }

}
