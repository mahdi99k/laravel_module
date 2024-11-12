<?php

namespace Webamooz\User\Http\Controllers\Auth;
//Auth مسیر پوشه ماژول تمامی کنتلر های پوشه (آث)  +  تمامی کنترلر های درون پوشه

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Webamooz\User\Http\Requests\ChangePasswordRequest;
use Webamooz\User\Services\UserService;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function showResetForm(Request $request)
    {
        $token = $request->route()->parameter('token');

        return view('User::Front.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }


    public function reset(ChangePasswordRequest $request)
    {
        UserService::changePassword(auth()->user(), $request->input('password'));
        return to_route('home');
    }

}
