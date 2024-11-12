<?php

namespace Webamooz\User\Http\Controllers\Auth;
//Auth مسیر پوشه ماژول تمامی کنتلر های پوشه (آث)  +  تمامی کنترلر های درون پوشه

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Webamooz\User\Http\Requests\ResetPasswordVerifyCodeRequest;
use Webamooz\User\Repositories\UserRepository;
use Webamooz\User\Services\VerifyCodeService;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function showVerifyCodeForm()
    {
        return view('User::Front.passwords.email');
    }

    public function sendVerifyCodeEmail(Request $request)
    {
        $user = (new UserRepository())->getByEmail($request->email);
        VerifyCodeService::forgetCache($user->id);

        if ($user) {  //اگر ایمیل کاربر وجود داشت ایمیلش بیا ارسال کد براش بفرست
            $user->sendResetPasswordRequestNotification();  //send notification for verify code reset password
            return view('User::Front.passwords.enter-verify-code-form');

        }else {
            return back()->withErrors(['not_exit_email' => 'این ایمیل در دیتابیس وجود ندارد!']);
        }
    }


    public function checkVerifyCode(ResetPasswordVerifyCodeRequest $request)
    {
        $user = resolve(UserRepository::class)->getByEmail($request->input('email'));  //resolve(name::class) === (new object())

        if ($user == null || !VerifyCodeService::checkVerifyCode($user->id, $request->verify_code)) {
            return back()->withErrors(['verify_code' => 'کد وارد شده معتبر نمی باشد!']);
        }

        auth()->loginUsingId($user->id);
        return redirect()->route('password.showResetForm');
    }

}
