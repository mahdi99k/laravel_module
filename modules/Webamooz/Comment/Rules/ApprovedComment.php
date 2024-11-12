<?php

namespace Webamooz\Comment\Rules;

use Illuminate\Contracts\Validation\Rule;
use Webamooz\Comment\Database\Repositories\CommentRepositories;

class ApprovedComment implements Rule
{

    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)  //$attribute(nameInput)=comment_id  +  $value=1
    {
        $commentRepo = new CommentRepositories();
        return !is_null($commentRepo->findByIdApproved($value));  //$value=comment_id + اگر کامنت وضعیت تایید شده بود و نال نبود
    }

    public function message()
    {
        return 'The validation error message.';
    }
}
