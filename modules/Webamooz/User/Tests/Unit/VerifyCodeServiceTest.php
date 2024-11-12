<?php

namespace Webamooz\User\Tests\Unit;

use Tests\TestCase;
use Webamooz\User\Services\VerifyCodeService;

class VerifyCodeServiceTest extends TestCase
{
    public function test_generate_code_is_6_digit()
    {
        $code = VerifyCodeService::generateRandomCode();
        //انتظار می رود حتما عدد باشسد  2)متن خطا سفارشی باشه
        $this->assertIsNumeric($code, "Generated Code Is Not Numeric");
        //انتظار می رود کوچیکتر یا مساوی باشد + 1)عدد برای تست ما  2)عددی که رکوست میش و ساخته میش  3)کد خطا سفارشی سازی شده
        $this->assertLessThanOrEqual(999999, $code, 'Generated Code Is Less Than 99999');
        //انتظار می رود بزرگتر یا مساوی باشد + 1)عدد برای تست ما  2)عددی که رکوست میش و ساخته میش  3)کد خطا سفارشی سازی شده
        $this->assertGreaterThanOrEqual(100000, $code, 'Generated Code Is Greater Than 100000');
    }

    public function test_verify_code_can_set_cache()
    {
        $code = VerifyCodeService::generateRandomCode();
        VerifyCodeService::setCache(1,$code , now()->addDay());
        $this->assertEquals($code , cache()->get('verify_code_1'));  //مقدار کد جنیرت شده با مقدار ولیو درون کش که با کلیدش بدست میاریم باید یکی باشد
    }
}
