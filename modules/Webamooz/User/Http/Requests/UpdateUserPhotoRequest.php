<?php

namespace Webamooz\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webamooz\User\Services\VerifyCodeService;

class UpdateUserPhotoRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'userPhoto' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:1024'],
        ];
    }
}
