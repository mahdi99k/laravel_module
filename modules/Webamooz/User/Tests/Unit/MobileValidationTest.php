<?php

namespace Webamooz\User\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Webamooz\User\Rules\ValidMobile;

class MobileValidationTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_mobile_can_not_be_less_than_10_character()
    {
        $result = (new ValidMobile())->passes('', '905618763');  //ی مدلی نوشتیم که وقتی اجرا بشه حالت خطا تست کنیم + assertEquals(0 -> False)
        $this->assertEquals(0, $result, 'mobile can not less 10 character');
        //assertEquals ادعا برابر است -> expected(false) مقدار با خطا مواجه , $result , message
    }

    public function test_mobile_can_not_be_more_than_10_character()
    {
        $result = (new ValidMobile())->passes('', '90561876312');  //ی مدلی نوشتیم که وقتی اجرا بشه حالت خطا تست کنیم + assertEquals(0 -> False)
        $this->assertEquals(0, $result, 'mobile can not more 10 character');
        //assertEquals ادعا برابر است -> expected(false) مقدار با خطا مواجه , $result , message
    }

    public function test_mobile_should_start_by_9()
    {
        $result = (new ValidMobile())->passes('', '2056187631');
        $this->assertEquals(0, $result);
    }

}
