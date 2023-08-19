<?php

namespace Webamooz\User\Http\Controllers\Auth;

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


    public function credentials(Request $request)
    {
        $username = $request->get($this->username());   //به صورت override اضافه میکنیم نه در ترید AuthenticatesUsers اصلی پروژه
        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';   //1)value(property)  2)filter(for value)
        $password = $request->get('password');
        return [
            $field => $username,
            'password' => $password
        ];
    }


    //-------------------- override method show view
    public function showLoginForm()
    {
        return view('User::Front.login');
    }

}
