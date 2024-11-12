<?php

namespace Webamooz\Payment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webamooz\Payment\Models\Settlement;

class SettlementRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $min = 10000;
        if (request()->method() == 'PATCH') {  //request()->getMethod() == 'PATCH
            return [
                'from.name' => "required_if:status," . Settlement::STATUS_SETTLED,  //from[name] + from[cart] -> اگر وضعیت تسویه شده باشد باید حتما پر بشن و واجب
                'from.cart' => "required_if:status," . Settlement::STATUS_SETTLED,
                'to.name' => "required_if:status," . Settlement::STATUS_SETTLED,  //وضعیت اسم و کارت گیرنده اجباری وقتی وضعیت روی تسویه شده + ?required_if:column,when
                'to.cart' => "required_if:status," . Settlement::STATUS_SETTLED,
                'amount' => "required|integer|min:$min",
            ];
        }

        return [
            'name' => 'required|max:255',
            'cart' => 'required|numeric|digits:16',
            'amount' => "required|integer|min:$min|max:" . auth()->user()->balance,  //حداکثر تا مقدار حساب من میتونه برداشت کنه نه بیشتر
        ];
    }

    public function attributes()
    {
        return [
            'cart' => 'شماره کارت',
            'amount' => 'مبلغ',
            'status' => 'وضعیت',
            'settled' => 'تسویه شده',
            'from.name' => 'نام صاحب حساب فرستنده',
            'from.cart' => 'شماره کارت فرستنده'
        ];
    }

    public function messages()
    {
        return [
            'cart.digits' => 'شماره کارت باید حتما 16 رقم باشد.'
        ];
    }

}
