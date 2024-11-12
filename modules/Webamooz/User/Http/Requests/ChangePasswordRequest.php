<?php

namespace Webamooz\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webamooz\User\Rules\ValidPassword;
use Webamooz\User\Services\VerifyCodeService;

class ChangePasswordRequest extends FormRequest
{

    public function authorize()
    {
        return auth()->check() == true; //اگر کسی لاگین کرده میتونه دسترسی داشته باش وگرنه نمیتونه کاری بکنه + وگرنه فالس
    }

    public function rules()
    {
        return [
            'password' => ['required', 'confirmed', new ValidPassword()],
        ];
    }

}
