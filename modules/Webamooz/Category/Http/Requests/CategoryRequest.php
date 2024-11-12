<?php

namespace Webamooz\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{

    public function authorize()
    {
        return auth()->check() == true; //اگر کسی لاگین کرده میتونه دسترسی داشته باش وگرنه نمیتونه کاری بکنه + وگرنه فالس
    }

    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'unique:categories,slug'],
            'parent_id' => ['nullable', 'numeric', 'exists:categories,id']
        ];
    }

}
