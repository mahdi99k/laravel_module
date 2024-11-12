<?php

namespace Webamooz\RolePermissions\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            //request()->route('role_permission') -> role_permission -> php artisan route:list -> PUT|PATCH role-permissions/{role_permission}
            'name' => ['required', 'string', 'min:3', 'max:200', 'unique:roles,name,' . request()->route('role_permission')],
            'permissions' => ['nullable', 'array', 'min:1']
        ];
    }
}
