<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidPassword implements Rule
{

    public function __construct()
    {
        //
    }


    public function passes($attribute, $value): bool|int
    {
        return preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{6,}$/' , $value);
        //1)regex  2)value(Validation name:'mobile' => ['nullable',max:255])
    }

    public function message(): string
    {
        return 'فرمت پسوورد اشتباه است.';
    }

}
