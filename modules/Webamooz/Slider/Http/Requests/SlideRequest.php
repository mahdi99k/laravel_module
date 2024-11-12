<?php

namespace Webamooz\Slider\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webamooz\Media\Services\MediaFileService;

class SlideRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'image' => 'required|file|mimes:' . MediaFileService::getExtensions(),
            'priority' => 'nullable|numeric|min:0',
            'link' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ];

        if (request()->method() == 'PATCH') {  //request()->getMethod()
            $rules['image'] = ['nullable', 'file', 'image', 'mimes:' . MediaFileService::getExtensions()];
        }
        return $rules;
    }

    public function attributes()
    {
        return [
            'image' => 'تصویر',
            'priority' => 'الویت بندی',
            'link' => 'لینک',
            'status' => 'وضعیت',
        ];
    }
}
