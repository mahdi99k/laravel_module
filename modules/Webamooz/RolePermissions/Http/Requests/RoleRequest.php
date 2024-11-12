<?php

namespace Webamooz\RolePermissions\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:200', 'unique:roles,name'],
            'permissions' => ['required', 'array', 'min:1']
        ];
    }
}
