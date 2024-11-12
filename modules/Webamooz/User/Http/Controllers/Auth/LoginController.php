<?php

namespace Webamooz\User\Http\Controllers\Auth;    //Auth مسیر پوشه ماژول تمامی کنتلر های پوشه (آث)  +  تمامی کنترلر های درون پوشه

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    protected $redirectTo = RouteServiceProvider::HOME;


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /*public function showLoginForm()  //override -> که درون اون فانکشن تغییر ندادم و همینجا از روش اور راید AuthenticatesUsers از درون ترید
    {
        return 'salam';
    }*/


    public function credentials(Request $request)  //override from use AuthenticatesUsers;
    {
        $entry = $request->get($this->username());
        $field = filter_var($entry, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';  //1)value(property)  2)filter(for value)
        $password = $request->input('password');
        return [
            $field => $entry,  //(mobile,email) => request->user
            'password' => $password
        ];
    }

    public function showLoginForm()
    {
        return view('User::Front.login');
    }

}
