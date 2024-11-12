<?php

namespace Webamooz\User\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Webamooz\User\Models\User;


class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;  //بعد از هر عملیات در دیتابیس های تست میاد رفرش مکینه دیتابیس -> migrate:refresh پاک میکنه همه چی

    public function test_user_can_see_reset_password_request_form()
    {
        $response = $this->get(route('password.request'))->assertOk();
//      $response->assertOk();
    }

    /*public function test_user_can_see_enter_verify_code_form_by_correct_email()
    {
        $user = User::create([
            'name' => 'mahdi',
            'email' => 'mahdi@gmail.com',
            'password' => '1Aas@31',
        ]);
        $this->call('get', route('password.sendVerifyCodeEmail'), ['email' => $user->email])
            ->assertOk();
    }*/

    public function test_user_can_not_see_enter_verify_code_form_by_wrong_email()
    {
        $user = User::create([
            'name' => 'mahdi',
            'email' => 'mahdi@gmail.com',
            'password' => '1Aas@31',
        ]);
        $this->call('get', route('password.sendVerifyCodeEmail'), ['email' => 'testNotExist@gmail.com']) //email faild -> redirect
          ->assertRedirect();
//        ->assertStatus(302);
    }

    public function test_user_banned_after_6_attempt_to_reset_password()  //بیشتر از پنج بار نوشت در یک دقیقه باید بن بشه
    {
        for ($i =0; $i < 5; $i++){
            //['verify_code' , 'email' => 'mahdi@gmail.com'] -> input(name) -> text(verify_code) + hidden(email->request()->email)
            $this->post(route('password.checkVerifyCode') , ['verify_code' , 'email' => 'mahdi@gmail.com'])
            ->assertStatus(302);
        }
        $this->post(route('password.checkVerifyCode') , ['verify_code' , 'email' => 'mahdi@gmail.com'])
            ->assertStatus(429);  //status 429 -> بیش از حد تلاش کردیم
    }
}
