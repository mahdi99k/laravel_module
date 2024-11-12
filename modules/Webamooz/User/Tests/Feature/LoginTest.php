<?php

namespace Webamooz\User\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Webamooz\User\Models\User;

class LoginTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_user_can_login_by_email()
    {
        $user = User::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'password' => bcrypt('A!12bc'),
            'email_verified_at' => now(),
        ]);
        $this->post(route('login'), [  //1)path  2)data
            'email' => $user->email,
            'password' => $user->password
        ]);
        $this->actingAs($user);
//      $this->assertAuthenticated();  //در آخر همین کاربر لاگین کن و احراز هویتش فعال کن
    }

    public function test_user_can_login_by_mobile()
    {
        $user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'mobile' => '9398187800',
            'password' => bcrypt('A!12bc'),
            'email_verified_at' => now(),
        ]);

        $this->post(route('login'), [
            'email' => $user->mobile,
            'password' => $user->password
        ]);

//      $this->assertAuthenticated();
    }


}
