<?php

namespace Webamooz\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
{

    public function authorize()
    {
        return auth()->check() == true; //اگر کسی لاگین کرده میتونه دسترسی داشته باش وگرنه نمیتونه کاری بکنه + وگرنه فالس
    }

    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            //request()->route('course') -> categories/{category} -> میاد میره درون روت و پارامتری که هست یا همون آیدی میگره میگه غیر این آیدی
            'slug' => ['required', 'string', 'unique:categories,slug,'.request()->route('category')],
            'parent_id' => ['nullable', 'numeric', 'exists:categories,id']
        ];

    }

}
