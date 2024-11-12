<?php

use Webamooz\Dashboard\Http\Controllers\DashboardController;

Route::group(['namespace' => 'Webamooz\Dashboard\Http\Controllers', 'middleware' => ['web', 'auth', 'verified']], function ($router) {
    $router->get('/home', [DashboardController::class, 'home'])->name('home');
});
