<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Webamooz\User\Models\User;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    //بعد از هر عملیات در دیتابیس های تست میاد رفرش مکینه دیتابیس -> migrate:refresh پاک میکنه همه چی

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_see_register_form()  //کاربری که میتونه فرم ثبت نام ببینه
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);  //برو ببین وضعیت 200 و موفقیت آمیز هست این ادرس ثبت نام ببینه
    }


    //--------------------


    public function test_user_can_register()
    {
        $this->withoutExceptionHandling();  //اصل خطا ببینیم نه خطا اکسپشن اول کد تست مینویسیم بدون اکسپشن هندل کن
        @csrf_token();  //method post
        $response = $this->registerNewUser();
        //---------- error ->  چون بعد ثبت نام میره ی صفحه دیگه نباید از وضعیت 200 استفاده کرد
        //$response->assertOk();  //تا اینجا کار درست کار میکنه یا نه مثل ()dd
        $response->assertRedirect(route('home'));

        //---------- error ->  تا قسمت بالا خطا میده که دیتابیس مشخص نکردی
        $this->assertCount(1, User::all());  //1)چه تعدادی انتظار داری  2)از چه منبعی این تعداد بگیره
    }



    //--------------------


    /* @return void */
    public function test_user_have_to_verify_account()  //کاربر آیا تایید شده ایمیلش
    {
        $this->registerNewUser();
        $response = $this->get(route('home'));
        $response->assertRedirect(route('verification.notice'));
    }


    public function test_user_verified_user_can_see_home_page()  //کاربر که تایید شده ببینه صفحه اصلی
    {
        $this->registerNewUser();
        $this->assertAuthenticated();  //بگو که کاربر لاگین شده حتما
        auth()->user()->markEmailAsVerified();    //درون وریفای مادل کاربر که میگه ایمیل کاربری که لاگین کرده Authenticated میکنه

        $response = $this->get(route('home'));
        $response->assertOk();
    }

    //---------- Ctl + Alt + M  -> method add
    public function registerNewUser()
    {
        return $this->post(route('register'), [
            'name' => 'reza',
            'email' => 'reza@gmail.com',
            'mobile' => '9398187800',
            'password' => 'Reza12!@',
            'password_confirmation' => 'Reza12!@',
        ]);
    }


}
