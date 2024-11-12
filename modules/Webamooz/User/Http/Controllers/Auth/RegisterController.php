<?php

namespace Webamooz\User\Http\Controllers\Auth;    //Auth مسیر پوشه ماژول تمامی کنتلر های پوشه (آث)  +  تمامی کنترلر های درون پوشه

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Webamooz\User\Models\User;  //*** change namespace
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Webamooz\User\Rules\ValidMobile;
use Webamooz\User\Rules\ValidPassword;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;


    public function __construct()
    {
        $this->middleware('guest');
    }


    /*
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'mobile' => ['nullable', 'string', 'min:9', 'max:14', 'unique:users,mobile', new ValidMobile()],
//          'mobile' => ['nullable', 'string', 'min:9', 'max:14', 'unique:users', 'regex:/^9[0-9]{9}/'], //regex:/^[a-zA-Z0-9@$#^%&*!]+$/u
            'password' => ['required', 'string', 'min:6', 'confirmed', new ValidPassword()],
        ]);
    }

    protected function create(array $data)
    {
        dd('passed');
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'password' => Hash::make($data['password']),
        ]);
        return to_route('website');
    }
    */

    protected function create(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'mobile' => ['nullable', 'string', 'min:9', 'max:14', 'unique:users,mobile', new ValidMobile()],
//          'mobile' => ['nullable', 'string', 'min:9', 'max:14', 'unique:users', 'regex:/^9[0-9]{9}/'], //regex:/^[a-zA-Z0-9@$#^%&*!]+$/u
            'password' => ['required', 'string', 'min:6', 'confirmed', new ValidPassword()],
        ]);

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'password' => Hash::make($request->input('password')),
        ]);
//      session()->flash('registerStore' , 'کاربر با موفقیت ثبت نام شد');
        return to_route('home');
    }

    public function showRegistrationForm()
    {
        return view('User::Front.register');
    }

}
