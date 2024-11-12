<?php

namespace Webamooz\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\User\Rules\ValidPassword;
use Webamooz\User\Services\VerifyCodeService;

class UpdateProfileInformationRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|min:3|max:200',
            'email' => 'required|email|max:200|unique:users,email,' . auth()->user()->id,
            'username' => 'nullable|string|unique:users,username,' . auth()->user()->id,
            'mobile' => 'nullable|numeric|unique:users,mobile,' . auth()->user()->id,
            'password' => ['nullable', new ValidPassword()]
        ];

        if (auth()->user()->hasPermissionTo(Permission::PERMISSION_TEACH)) {
            $rules += [
                'card_number' => 'required|string|size:16',
                'sheba' => 'required|string|size:24',
                'head_line' => 'required|string|min:3|max:100',
                'telegram' => 'required|string',
                'bio' => 'required|string',
            ];
        }
//      $rules['username'] = 'required|string|unique:users,username,' . auth()->user()->id;

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => 'نام',
            'email' => 'ایمیلی',
            'username' => 'نام کابری',
            'mobile' => 'موبایل',
            'password' => 'رمز عبور جدید',
            'card_number' => 'شماره کارت بانکی',
            'sheba' => 'شماره شبای بانکی',
            'head_line' => 'عنوان',
            'telegram' => 'تلگرام',
            'bio' => 'بیوگرافی'
        ];
    }
}
