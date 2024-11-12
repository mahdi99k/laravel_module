<?php

namespace Webamooz\User\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidMobile implements Rule
{

    public function __construct()
    {
        //
    }

    public function passes($attribute, $value): bool|int
    {
        ///^9[0-9]{9}$/  ->  ^ شروع رجکس + {9} با عدد ۹ شروع بشه + [0-9] بین صفر تا ۹ باشد + 9 از 9 کاراکتر بیشتر نشه + & \ایان رجکس بسته شدن
        return preg_match('/^9[0-9]{9}$/' , $value);  //1)regex  2)value(Validation name:'mobile' => ['nullable',max:255])
    }

    public function message(): string
    {
        return 'فرمت موبایل نامعتبر است.شماره موبایل باید با عدد 9 شروع شود و بدون فاصله وارد شود.';
    }
}

