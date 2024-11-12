<?php

namespace Webamooz\Course\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Webamooz\Course\Models\Course;
use Webamooz\Course\Rules\ValidSeason;

class CourseRequest extends FormRequest
{

    public function authorize()
    {
        return auth()->check() == true;
    }

    public function rules()
    {
        $rules = [
            'teacher_id' => ['required', 'exists:users,id'],
            'category_id' => 'required|numeric|exists:categories,id',
            'title' => 'required|min:3|max:200',
            'slug' => 'required|min:3|max:200|unique:courses,slug',
            'priority' => 'required|numeric',
            'price' => 'required|numeric|min:0|max:10000000',
            'percent' => 'required|numeric|min:0|max:100',
            'type' => ['required', Rule::in(Course::$types)],  //Rule::in(array) -> مقدار آرایه میگیره و فقط این مقدار ها باید بگیره
            'status' => ['required', Rule::in(Course::$statuses)],  //Rule::in(array) -> مقدار آرایه میگیره و فقط این مقدار ها باید بگیره
//          'confirmation_status' => ['required', Rule::in(Course::$confirmationStatuses)],  //Rule::in(array) -> مقدار آرایه میگیره و فقط این مقدار ها باید بگیره
            'image' => 'required|max:1024|mimes:png,jpg,jpeg',
            'body' => 'nullable|string',
        ];

        if (request()->method == "PATCH") {  //request updated
            $rules['image'] = 'nullable|image|max:1024|mimes:png,jpg,jpeg';
            //request()->route('course') -> categories/{course} -> میاد میره درون روت و پارامتری که هست یا همون آیدی میگره میگه غیر این آیدی
            $rules['slug'] = 'required|min:3|max:200|unique:courses,slug,' . request()->route('course');
        }


        return $rules;
    }


    public function attributes()
    {
        return [
            'slug' => 'اسلاگ',
            'image' => 'تصویر',
            'type' => 'نوع',
            'status' => 'وضعیت',
            'confirmation_status' => 'وضعیت',
            'teacher_id' => 'مدرس دوره',
            'category_id' => 'دسته بندی',
            'percent' => 'در صد',
            'price' => 'قیمت',
            'priority' => 'ردیف',
            'body' => 'توضیحات'
        ];
    }


    public function messages()
    {
        return [];

         /*return [
            'price.required' => trans('Course::validation.price_required'),  //Course(namespace) + validation + key
            'price.min' => trans('Course::validation.price_min'),
            'price.max' => trans('Course::validation.price_max')
        ];*/

    }

}
