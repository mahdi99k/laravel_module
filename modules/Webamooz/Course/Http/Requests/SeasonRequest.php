<?php

namespace Webamooz\Course\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Webamooz\Category\Rules\ValidSeason;
use Webamooz\Course\Models\Course;

class SeasonRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() == true;
    }

    public function rules()
    {
        return [
            'title' => 'required|min:3|max:200',
            'number' => 'nullable|numeric|min:0|max:250',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'عنوان سر فصل',
            'number' => 'شماره سر فصل',
        ];
    }
}
