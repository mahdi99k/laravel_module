<?php

namespace Webamooz\User\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Webamooz\User\Rules\ValidPassword;

class PasswordValidationTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_password_can_not_be_less_than_6_character()
    {
        $result = (new ValidPassword())->passes('' , '12Ab!');  //همه موارد در پسوورد رعایت شده به جز تعداد کاراکتر(حداقل 6 کاراکتر)
        $this->assertEquals(0 , $result);  //assertEquals ادعا برابر است -> expected(false) مقدار با خطا مواجه , $result , message
    }

    public function test_password_should_include_sign_character()
    {
        $result = (new ValidPassword())->passes('' , '123aBC');  //همه موارد در پسوورد رعایت شده به جز وجود کاراکتر های ویژه
        $this->assertEquals(0 , $result);  //assertEquals ادعا برابر است -> expected(false) مقدار با خطا مواجه , $result , message
    }

    public function test_password_should_include_digit_character()
    {
        $result = (new ValidPassword())->passes('' , 'abCD!@');  //همه موارد در پسوورد رعایت شده به جز وجود اعداد
        $this->assertEquals(0 , $result);
    }

    public function test_password_should_include_capital_character()
    {
        $result = (new ValidPassword())->passes('' , 'abc12!');  //همه موارد در پسوورد رعایت شده به جز وجود حروف بزرگ انگلیسی
        $this->assertEquals(0 , $result);
    }

    public function test_password_should_include_lower_character()
    {
        $result = (new ValidPassword())->passes('' , 'ABC12!');  //همه موارد در پسوورد رعایت شده به جز وجود حروف ک.چک انگلیسی
        $this->assertEquals(0 , $result);
    }

}
