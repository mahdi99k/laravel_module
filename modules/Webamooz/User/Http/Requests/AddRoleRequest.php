<?php

namespace Webamooz\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webamooz\User\Services\VerifyCodeService;

class AddRoleRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'role' => ['required', 'exists:roles,name'],
        ];
    }
}
