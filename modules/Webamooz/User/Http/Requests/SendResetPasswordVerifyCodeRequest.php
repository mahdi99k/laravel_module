<?php

namespace Webamooz\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendResetPasswordVerifyCodeRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email'
        ];
    }
}
