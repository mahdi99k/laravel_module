<?php

namespace Webamooz\User\Services;

class VerifyCodeService
{

    private static $min = 100000;
    private static $max = 999999;
    private static $prefix = 'verify_code_';

    public static function generateRandomCode()
    {
        return random_int(self::$min, self::$max);  //get number code dynamic
    }

    public static function setCache($user_id, $code, $time)
    {
        //1)Key(اسم دیتا چی باش) + 2)Value(چه مقداری بگیره) + 3)Data(چه دیتایی ست کنه)  +  4)now()->addDay() پیش فرض یک روز
        cache()->set(self::$prefix . $user_id, $code, $time);
    }

    public static function getCache($user_id)
    {
        return cache()->get(self::$prefix . $user_id);
    }

    public static function hasCodeCache($user_id)
    {
        return cache()->has(self::$prefix . $user_id);  //check has ,cache by key
    }

    public static function forgetCache($user_id)
    {
        return cache()->forget(self::$prefix . $user_id);
//      return cache()->delete('verify_code' . $user_id);
    }

    public static function getRule()
    {
        return 'required|numeric|between:' . self::$min . ',' . self::$max;  //between:100000,999999
    }

    public static function checkVerifyCode($id, $code)
    {
        if (self::getCache($id) != $code) return false;

        self::forgetCache($id);  //delete code(if true)
        return true;
    }


}
