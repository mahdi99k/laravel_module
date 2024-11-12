<?php

namespace Webamooz\Payment\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webamooz\Course\Models\Course;
use Webamooz\Payment\Gateways\Gateway;
use Webamooz\Payment\Gateways\Zarinpal\ZarinpalAdaptor;
use Webamooz\Payment\Models\Payment;
use Webamooz\Payment\Models\Settlement;
use Webamooz\Payment\Policies\SettlementPolicy;
use Webamooz\RolePermissions\Model\Permission;

class PaymentServiceProvider extends ServiceProvider
{
    public string $namespace = "Webamooz\Payment\Http\Controllers";

    public function register()
    {
        $this->app->register(EventServiceProvider::class);
        Route::middleware(['web'])->namespace($this->namespace)->group(__DIR__ . "/../Routes/payment_route.php");
        Route::middleware(['web'])->namespace($this->namespace)->group(__DIR__ . "/../Routes/settlement_routes.php");
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views/', "Payment");
        $this->loadJsonTranslationsFrom(__DIR__ . '/../Lang/');  //file ترجمه کلممات به صورت جیسون
        Gate::policy(Settlement::class, SettlementPolicy::class);  //1)Model::class  2)Policy::class
    }

    public function boot()
    {
        config()->set('sidebar.items.payments', [
            'icon' => 'i-transactions',
            'title' => 'تراکنش ها',
            'url' => route('payments.index'),
            'permission' => [
                Permission::PERMISSION_MANAGE_PAYMENTS,
            ]
        ]);

        config()->set('sidebar.items.my-purchases', [
            'icon' => 'i-my__purchases',
            'title' => 'خرید های من',
            'url' => route('purchases.index'),
        ]);

        config()->set('sidebar.items.settlements', [
            'icon' => 'i-checkouts',
            'title' => 'تسویه حساب ها',
            'url' => route('settlements.index'),
            'permission' => [
                Permission::PERMISSION_MANAGE_SETTLEMENTS,
                Permission::PERMISSION_TEACH,
            ]
        ]);

        config()->set('sidebar.items.settlements_create', [
            'icon' => 'i-checkout__request',
            'title' => 'درخواست تسویه',
            'url' => route('settlements.create'),
            'permission' => [
                Permission::PERMISSION_TEACH,
            ]
        ]);

        //فرق بین new و resolve این در ریزالو میتونیم وقتی کلاس صدا زدیم بیایم در سرویس پرووایدر ی مقدار هایی بهش بدیم
        $this->app->singleton(Gateway::class, function ($app) {
            return new ZarinpalAdaptor();
        });

        /*Course::resolveRelationUsing('payments', function ($courseModel) {  //1)name relation + اضافه کردن ریلیشن دوره ها در سرویس پرووایدر پیمنت
            return $courseModel->morphMany(Payment::class, 'paymentable');
        });*/
    }

}
