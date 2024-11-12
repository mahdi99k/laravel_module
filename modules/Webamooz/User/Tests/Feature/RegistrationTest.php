<?php

namespace Webamooz\User\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use Webamooz\RolePermissions\Database\Seeders\RolePermissionTableSeeder;
use Webamooz\User\Models\User;
use Webamooz\User\Services\VerifyCodeService;

class RegistrationTest extends TestCase
{
    use RefreshDatabase,WithFaker;

    //بعد از هر عملیات در دیتابیس های تست میاد رفرش مکینه دیتابیس -> migrate:refresh پاک میکنه همه چی


    public function test_user_can_see_register_form()  //کاربری که میتونه فرم ثبت نام ببینه
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);  //آیا تونست صفحه به درستی ببینه و روت پیدا کرد و لود شد کمل صفحه
    }


    public function test_user_can_register()
    {
        $this->withoutExceptionHandling();  //اصل خطا ببینیم نه خطا اکسپشن اول کد تست مینویسیم بدون اکسپشن هندل کن
        @csrf_token();  //method post
        $response = $this->registerNewUser();

        //---------- error ->  چون بعد ثبت نام میره ی صفحه دیگه نباید از وضعیت 200 استفاده کرد
        //$response->assertOk();  //تا اینجا کار درست کار میکنه یا نه مثل ()dd
        $response->assertRedirect(route('home'));

        $this->assertCount(1, User::all());  //1)چه تعدادی انتظار داری  2)از چه منبعی این تعداد بگیره
    }

    public function test_user_can_verify_account()
    {
        $user = User::create([
            'name' => 'mahdi',
            'email' => 'mahdi@gmail.com',
            'password' => 'a!12ABC'
        ]);
        $code = VerifyCodeService::generateRandomCode();
        VerifyCodeService::setCache($user->id, $code, now()->addDay());
        $getCodeCache = VerifyCodeService::getCache($user->id);

        auth()->loginUsingId($user->id);
        $this->assertAuthenticated();  //authentication user

        $this->post(route('verification.verify'), [
            'verify_code' => $getCodeCache,  //تایید کد برابر کد اسرالی بود در درون کش
        ]);

        $this->assertEquals(true, $user->fresh()->hasVerifiedEmail());
        //بیا ببین کاربر ایمیلش verify شده + fresh آخرین اطلاعات از دیتابیس میگیره میخونه و تازه سازی میکنه
    }

    public function test_user_have_to_verify_account()  //کاربر آیا تایید شده ایمیلش
    {
        $this->registerNewUser();
        $this->createUserNotVerify();
        $response = $this->get(route('home'));
        $response->assertRedirect(route('verification.notice'));  //page verified email see
    }

    public function test_verified_user_can_see_home_page()  //کاربر که تایید شده ببینه صفحه اصلی
    {
        $this->withoutExceptionHandling();
        $this->registerNewUser();
        $this->createUser();
//      $this->assertAuthenticated();  //بگو که کاربر لاگین شده حتما
//      auth()->user()->markEmailAsVerified();   //اون کاربری که لاگین شده میاد به صورت دستی میایم ایمیل وریفای فعال مکنیم
        $response = $this->get(route('home'));
        $response->assertOk();
    }

    public function createUser()
    {
        $user = User::create([
            'name' => 'mahdi',
            'email' => $this->faker->unique()->safeEmail(),
            'mobile' => '939818' . rand(1000, 9999),
            'email_verified_at' => now(),
            'password' => \Hash::make('12aBC!@'),
            'remember_token' => Str::random(10),
        ]);
        $this->actingAs($user);  //actingAs -> authentication + factory(User::class))->create() -> create user
        $this->seed(RolePermissionTableSeeder::class);
    }

    public function createUserNotVerify()
    {
        $user = User::create([
            'name' => 'mahdi',
            'email' => $this->faker->unique()->safeEmail(),
            'mobile' => '939818' . rand(1000, 9999),
            'password' => \Hash::make('12aBC!@'),
            'remember_token' => Str::random(10),
        ]);
        $this->actingAs($user);  //actingAs -> authentication + factory(User::class))->create() -> create user
        $this->seed(RolePermissionTableSeeder::class);
    }



    public function registerNewUser(): \Illuminate\Testing\TestResponse
    {
        return $this->post(route('register'), [
            'name' => 'mahdi',
            'email' => 'mahdi@gmail.com',
            'mobile' => '9398187800',
            'password' => 'a!12ABC',
            'password_confirmation' => 'a!12ABC',
            'email_verified_at' => now(),
        ]);
    }

}
