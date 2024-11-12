<?php

namespace Webamooz\Payment\Routes;

use Illuminate\Support\Facades\Route;
use Webamooz\Payment\Http\Controllers\SettlementController;

Route::group(["middleware" => 'auth'], function ($router) {
//  $router->get('settlements', [SettlementController::class, 'index'])->name('settlements.index');
    $router->get('settlements', [
        "uses" => "SettlementController@index",
        "as" => "settlements.index"
    ]);
    $router->get('settlements/create', [
        "uses" => "SettlementController@create",
        "as" => ("settlements.create")  //هم با پرانتز هم بدون پرانتز درسته
    ]);
    $router->post('settlements', [SettlementController::class, 'store'])->name('settlements.store');
    $router->get('settlements/{settlement}/edit', [SettlementController::class, 'edit'])->name('settlements.edit');
    $router->patch('settlements/{settlement}', [SettlementController::class, 'update'])->name('settlements.update');
});
