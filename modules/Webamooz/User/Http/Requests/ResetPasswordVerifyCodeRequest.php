<?php

namespace Webamooz\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webamooz\User\Services\VerifyCodeService;

class ResetPasswordVerifyCodeRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'verify_code' => VerifyCodeService::getRule(),
            'email' => 'required|email'
        ];
    }
}
