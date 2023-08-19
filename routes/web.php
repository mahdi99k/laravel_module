<?php

use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('index');
});

Route::get('/email', function () {
    return view('auth.passwords.email');
});


Route::get('/reset', function () {
//  return view('auth.reset-password');
    return view('auth.passwords.reset');
});

//Auth::routes(['verify' => true]);  //default -> verify=false   دستی میایم فعال میکنیم

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');







//---------------------- Lesson-22                    TIME(00:00)                    today-1,0.5




