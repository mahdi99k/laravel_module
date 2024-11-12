<?php

use Illuminate\Support\Facades\Route;
use Webamooz\User\Http\Controllers\Auth\ForgotPasswordController;
use Webamooz\User\Http\Controllers\Auth\LoginController;
use Webamooz\User\Http\Controllers\Auth\RegisterController;
use Webamooz\User\Http\Controllers\Auth\ResetPasswordController;
use Webamooz\User\Http\Controllers\Auth\VerificationController;
use Webamooz\User\Http\Controllers\UserController;


Route::group(['namespace' => 'Webamooz\User\Http\Controllers', 'middleware' => ['web', 'auth']], function ($router) {
    //---------- dashboard users
//  Route::post('/users/{user}/add/role', [UserController::class, 'addRole'])->name('users.add.role');
    Route::delete('/users/{user}/remove/role/{role}', [UserController::class, 'removeRole'])->name('users.remove.role');
    Route::patch('/users/{user}/manualVerify', [UserController::class, 'manualVerify'])->name('users.manualVerify');
    Route::post('/users/photo', [UserController::class, 'updatePhoto'])->name('users.photo');
    Route::get('/users/profile', ["uses" => "UserController@profile", "as" => "users.profile"]);
    Route::post('/users/profile', [UserController::class, 'updateProfile'])->name('users.profile.update');
//  Route::get('/tutors/{username}', [UserController::class, 'viewProfile'])->name('users.view.profile');
    Route::resource('users', UserController::class);  //ریسورس میزاریم آخر با روت های قبلی اشتباه نگیر + الویت روت های دستی نوشتیم بالاتر
    Route::get('users/{user}/full_info', [UserController::class, 'fullInfo'])->name('users.full_info');
});


Route::group(['namespace' => 'Webamooz\User\Http\Controllers', 'middleware' => 'web'], function ($router) {
    //---------- Email verify
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');  //نمایش صفحه اسال کد
    Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');  //ارسال کد به ایمیل
    Route::post('/email/verify', [VerificationController::class, 'verify'])->name('verification.verify');  //تایید شدن ایمیل

    //---------- Register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'create'])->name('register');

    //---------- Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login');

    //---------- Logout
    Route::any('/logout', [LoginController::class, 'logout'])->name('logout');  //any -> get,post,put,patch,delete


    //---------- reset password
    Route::get('/password/reset', [ForgotPasswordController::class, 'showVerifyCodeForm'])->name('password.request');  //show form + send email
    Route::get('/password/reset/send', [ForgotPasswordController::class, 'sendVerifyCodeEmail'])
        ->name('password.sendVerifyCodeEmail')->middleware('throttle:5,30');  //middleware('throttle:(numberRequest چند بار درخواست میتونه بده),(minutes محاسبه درخواست ها در دقیقه)')

    Route::post('/password/reset/check_verify_code', [ForgotPasswordController::class, 'checkVerifyCode'])
        ->name('password.checkVerifyCode')->middleware('throttle:5,1');
    Route::get('/password/change', [ResetPasswordController::class, 'showResetForm'])->name('password.showResetForm')->middleware('auth');
    Route::post('/password/change', [ResetPasswordController::class, 'reset'])->name('password.update');
});




//password : Q!w2e3  //(userId more 5)
