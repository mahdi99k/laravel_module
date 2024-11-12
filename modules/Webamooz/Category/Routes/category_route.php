<?php

use Webamooz\Category\Http\Controllers\CategoryController;

Route::group(['namespace' => 'Webamooz\Category\Http\Controllers', 'middleware' => ['web', 'auth', 'verified']], function ($router) {
    $router->resource('/categories', CategoryController::class);/*->middleware('permission:manage categories')*/
});
