<?php

namespace Tests\Unit;

use App\Rules\ValidPassword;
use PHPUnit\Framework\TestCase;

class PasswordValidationTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_password_should_not_be_less_than_6_character()  //رمز عبور نباید کمتر از ۶ کاراکتر باشد
    {
        $result = new ValidPassword();
        $passes = $result->passes('', '12345');  //1)attributeکدوم اتریبیوت  Two)value مقدار چی هست برای تست
        $this->assertEquals(0, $passes);
    }

    public function test_password_should_not_include_sign_character()  //رمز عبور نباید sign (!@#$%^) داشته باشد
    {
        $result = new ValidPassword();
        $passes = $result->passes('', 'A12a25gs4');  //1)attributeکدوم اتریبیوت  Two)value مقدار چی هست برای تست
        $this->assertEquals(0, $passes);
    }

    public function test_password_should_not_include_digit_character()  //رمز عبور نباید عدد داشته
    {
        $result = new ValidPassword();
        $passes = $result->passes('', 'B!@!asdD');  //1)attributeکدوم اتریبیوت  Two)value مقدار چی هست برای تست
        $this->assertEquals(0, $passes);
    }

    public function test_password_should_not_include_capital_character()
    {
        $result = (new ValidPassword())->passes('', '12!@AA');
        $this->assertEquals(0, $result);
    }

    public function test_password_should_not_include_small_character()
    {
        $result = (new ValidPassword())->passes('', '12!@PIT34$');
        $this->assertEquals(0, $result);
    }

}
