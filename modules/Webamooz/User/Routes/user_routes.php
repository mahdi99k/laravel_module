<?php

namespace Webamooz\User\Routes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::group(['namespace' => 'Webamooz\User\Http\Controllers', 'middleware' => 'web'], function ($router) {
    Auth::routes(['verify' => true]);  //default -> verify=false   دستی میایم فعال میکنیم
});



//'namespace' => 'App\Http\Controllers' -> show page    +    'middleware' => 'web' -> Undefined variable $errors






