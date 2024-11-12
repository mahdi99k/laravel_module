<?php

use App\Events\PaymentWasSuccessful;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Webamooz\Payment\Gateways\Gateway;
use Webamooz\Payment\Repositories\PaymentRepositories;


//----- Route Tests
Route::get('/testMail', function () {
    return (new \Webamooz\User\Mail\VerifyCodeMail(auth()->user()->name, random_int(100000, 999999)));  //داخل کلاس VerifyCodeMail ویو پاس دادیم
});

//---------- temporarySignedRoutes امضای مسیر موقت
Route::get('/verify-link/{user_id}', function () {
    if (request()->hasValidSignature()) {
        return "ok";
    } else {
        return "Not Valid";
    }
})->name('verify-link');

Route::get('/test', function () {
    event(new \Webamooz\Payment\Events\PaymentWasSuccessful(\Webamooz\Payment\Models\Payment::first()));
    /*$gateway = resolve(Gateway::class);  //فرقش با نیو این میتونیم وقتی صدا زدیم در سرویس پرووایدر بگیم بیا قبلش این کارا انجام بده
    $payment = new \Webamooz\Payment\Models\Payment();
    return $gateway->request($payment);*/

    //name(route برای کدوم روت)  2)expire  3)data(parameters exist)
//    $url = URL::temporarySignedRoute('verify-link', now()->seconds(100), ['user_id' => 1]);
//    dd($url);
});
//---------- temporarySignedRoutes


//---------- test create permission spatie
Route::get('/testPermission', function () {

    /*
      \Spatie\Permission\Models\Permission::create([  //table -> permissions
            'name' => 'manage role_permissions'
        ]);

    auth()->user()->givePermissionTo(\Webamooz\RolePermissions\Model\Permission::PERMISSION_MANAGE_COURSES);  //tables -> model_has_permissions
    //  return auth()->user()->permissions;  //show all permissions user
    */

//  auth()->user()->assignRole('teacher');  //insert role

});
//---------- test create permission spatie



