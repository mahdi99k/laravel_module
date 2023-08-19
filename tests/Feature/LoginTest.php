<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Webamooz\User\Models\User;

class LoginTest extends TestCase
{

    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_login_by_email()
    {
        $user = User::query()->create([
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => bcrypt('Mahdi12!@'),
        ]);

        $this->post(route('login'), [  //1)path  2)data
            'email' => $user->email,
            'password' => 'Mahdi12!@'
        ]);
        $this->assertAuthenticated();  //در آخر همین کاربر لاگین کن و احراز هویتش فعال کن
    }


    public function test_user_can_login_by_mobile()
    {
        $user = User::query()->create([
            'name' => $this->faker->firstName,
            'email' => $this->faker->safeEmail(),
            'mobile' => '9398187801',
            'password' => bcrypt('RezaBh12!@')
        ]);

        $this->post(route('login'), [  //1)path  2)data
            'email' => $user->mobile,
            'password' => 'RezaBh12!@'
        ]);
        $this->assertAuthenticated();
    }


}
