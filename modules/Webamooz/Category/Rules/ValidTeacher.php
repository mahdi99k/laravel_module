<?php

namespace Webamooz\Category\Rules;

use Illuminate\Contracts\Validation\Rule;
use Webamooz\User\Repositories\UserRepository;

class ValidTeacher implements Rule
{

    public function passes($attribute, $value)  //$value -> user_id
    {
        $user = resolve(UserRepository::class)->findById($value);
        return $user->hasPermissionTo('teach');  //true ->next  +  false -> show error message
    }

    public function message()
    {
        return "کاربر انتخاب شده یک مدرس معتبر نیست!";
    }

}
