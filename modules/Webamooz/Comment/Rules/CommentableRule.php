<?php

namespace Webamooz\Comment\Rules;

use Illuminate\Contracts\Validation\Rule;

class CommentableRule implements Rule
{

    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)  //$attribute(nameInput)=commentable_type + $value=Webamooz\Course\Models\Course
    {
        return class_exists($value) && method_exists($value,"comments");  //method_exists -> One)درون آبجکت یا مادل دوره برو بگرد  Two)درون مادل بگرد ببین همچین متودی داره
    }

    public function message()
    {
        return 'The validation error message.';
    }
}
