<?php

namespace Webamooz\User\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidPassword implements Rule
{

    public function __construct()
    {
        //
    }

    public function passes($attribute, $value): bool|int
    {
        //1)regex  2)value(Validation name:'password' => ['nullable',max:255])
        return preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{6,}$/' , $value);
    }

    public function message(): string
    {
        return 'فرمت پسوورد اشتباه است.';
    }

}
