<?php

use Webamooz\Slider\Http\Controllers\SlideController;
use \Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function ($router) {
    $router->resource('/slides', SlideController::class);
});
