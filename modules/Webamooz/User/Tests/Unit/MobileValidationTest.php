<?php

namespace Webamooz\User\Tests\Unit;

use App\Rules\ValidMobile;
use PHPUnit\Framework\TestCase;

class MobileValidationTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    //----- part 1
    public function test_mobile_can_not_be_less_than_10_character()  //شماره موبایل نمیتونه کمتر از ۹ کاراکتر باشد
    {
        $result = new ValidMobile();  //passes -> عبور می کند
        //
        $passes = $result->passes('' , '939818780');  //1)attributeکدوم اتریبیوت  Two)value مقدار چی هست برای تست
        $this->assertEquals(0 , $passes);  //assertEquals ادعا برابر است
    }


    //----- part 2
    public function test_mobile_can_not_be_more_than_10_character()  //شماره موبایل نمیتونه بیشتز از ۹ کاراکتر باشد
    {
        $result = (new ValidMobile())->passes('' , '93981878001');  //نمونه کد که مینویسیم درون passes میاد تعیین میکنه درست یا غلط تست
        $this->assertEquals(0 , $result);  //assertEquals ادعا برابر است
    }

    public function test_mobile_should_start_by_9()  //شماره موبایل باید شروعش با عدد ۹ باشدد
    {
        $result = (new ValidMobile())->passes('' , '3939818780');  //نمونه کد که مینویسیم درون passes میاد تعیین میکنه درست یا غلط تست
        $this->assertEquals(0 , $result);  //assertEquals ادعا برابر است
    }


}
