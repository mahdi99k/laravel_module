<?php

namespace Webamooz\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webamooz\User\Services\VerifyCodeService;

class VerifyCodeRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'verify_code' => VerifyCodeService::getRule(),
        ];
    }

    /*
     public function messages()
    {
        return [
            'verify_code.between' => 'اعداد باید بین 1000000 تا 999999 باشد'
        ];
    }
    */
}
