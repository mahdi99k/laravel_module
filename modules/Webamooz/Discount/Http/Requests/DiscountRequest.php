<?php

namespace Webamooz\Discount\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webamooz\Discount\Rules\ValidJalaliDate;

class DiscountRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        $rules = [
            'code' => ['nullable', 'string', 'max:50', 'unique:discounts,code'],
            'percent' => "required|numeric|min:1|max:100",
            'usage_limitation' => "nullable|numeric|min:0|max:1000000000",
            'expire_at' => ["nullable", new ValidJalaliDate()],
            "courses" => "nullable|array",
            'link' => "nullable|url",
            'description' => "nullable|string",
            'type' => "required",
        ];

        if (request()->method() == 'PATCH') {  //request()->getMethod() == 'PATCH
            //request()->route('course') -> categories/{category} -> میاد میره درون روت و پارامتری که هست یا همون آیدی میگره میگه غیر این آیدی
            $rules['code'] = ['nullable', 'string', 'max:50', 'unique:discounts,code,' . request()->route('discount')->id];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            "code" => "کد تخفیف",
            "percent" => "درصد تخفیف",
            "type" => "نوع تخفیف",
            "usage_limitation" => "محدودیت افراد",
            "link" => "لینک اطلاعات",
        ];
    }

    /*public function messages()
    {

    }*/

}
