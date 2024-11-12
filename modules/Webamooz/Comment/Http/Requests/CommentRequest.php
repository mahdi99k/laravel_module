<?php

namespace Webamooz\Comment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webamooz\Comment\Rules\ApprovedComment;
use Webamooz\Comment\Rules\CommentableRule;

class CommentRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'body' => ['required', 'string'],
            'comment_id' => ['nullable', 'numeric' , new ApprovedComment()],  //ApprovedComment -> کانت ما باید تایید شده باشد تا پاسخی بتونیم بنویسیم براش
            'commentable_id' => ['required', 'numeric'],
            'commentable_type' => ['required', 'string', new CommentableRule()]
        ];
    }

    public function attributes()
    {
        return [
            'body' => 'متن نظر'
        ];
    }
}
