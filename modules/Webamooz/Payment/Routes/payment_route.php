<?php

namespace Webamooz\Payment\Routes;

use Illuminate\Support\Facades\Route;
use Webamooz\Payment\Http\Controllers\PaymentController;

Route::group([], function ($router) {
    $router->any('payments/callback', [PaymentController::class, 'callback'])->name('payments.callback');

    $router->get('payments', [  //سرویس پروایدر نمیشناخت اسم روت ما مجبور شدیم این مدلی بنویسیم
        "uses" => "PaymentController@index",
        "as" => ("payments.index")  //->name('')
    ]);

    $router->get('purchases/index', [
        "uses" => "PaymentController@purchases",
        "as" => ("purchases.index")
    ]);
});
