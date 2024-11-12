<?php

namespace Webamooz\Course\Rules;

use Illuminate\Contracts\Validation\Rule;
use Webamooz\Course\Repositories\SeasonRepository;
use Webamooz\User\Repositories\UserRepository;

class ValidSeason implements Rule
{

    public function passes($attribute, $value)  //$value -> user_id
    {
        $season = resolve(SeasonRepository::class)->findByIdAndCourseId($value, request()->route('course'));
        if ($season) {
            return true;
        }
        return false;
    }

    public function message()
    {
        return "سر فصل انتخاب شده معتبر نمی باشد!";
    }

}
