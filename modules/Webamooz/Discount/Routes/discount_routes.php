<?php

namespace Webamooz\Discount\Routes;

use Illuminate\Support\Facades\Route;
use Webamooz\Discount\Http\Controllers\DiscountController;

Route::group(["middleware" => "auth"], function ($router) {
    $router->get('/discounts', [
        "uses" => "DiscountController@index",
        "as" => "discounts.index"
    ]);
    $router->post('/discounts', [DiscountController::class, 'store'])->name('discounts.store');
    $router->get('/discounts/{discount}/edit', [DiscountController::class, 'edit'])->name('discounts.edit');
    $router->patch('/discounts/{discount}', [DiscountController::class, 'update'])->name('discounts.update');
    $router->delete('/discounts/{discount}', [DiscountController::class, 'destroy'])->name('discounts.destroy');

    //middleware('throttle:(numberRequest چند بار درخواست میتونه بده),(minutes محاسبه درخواست ها در دقیقه)')
    $router->get("/discounts/{code}/{course}/check", [DiscountController::class, 'check'])->name('discounts.check')->middleware('throttle:6,1');
});

