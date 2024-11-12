<?php

namespace Webamooz\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Webamooz\Category\Rules\ValidSeason;
use Webamooz\Course\Models\Course;
use Webamooz\User\Models\User;

class UpdateUserRequest extends FormRequest
{

    public function authorize()
    {
        return auth()->check() == true;
    }

    public function rules()
    {
        return [  //name input -> request
            'name' => 'required|min:3|max:200',
            'email' => 'required|email|max:200|unique:users,email,' . request()->route('user'),  //id -> PUT|PATCH users/{user}
            'username' => 'nullable|string|unique:users,username,' . request()->route('user'),  //id -> PUT|PATCH users/{user}
            'mobile' => 'nullable|numeric|unique:users,mobile,' . request()->route('user'),  //id -> PUT|PATCH users/{user}
            'head_line' => 'nullable|string',
            'website' => 'nullable|url|max:300',
            'linkedin' => 'nullable|url|max:300',
            'facebook' => 'nullable|url|max:300',
            'twitter' => 'nullable|url|max:300',
            'youtube' => 'nullable|url|max:300',
            'instagram' => 'nullable|url|max:300',
            'telegram' => 'nullable|url|max:300',
            'status' => ['nullable', Rule::in(User::$statuses)],  //Rule::in(array) -> مقدار آرایه میگیره و فقط این مقدار ها باید بگیره
            'image' => 'nullable|max:1024|mimes:png,jpg,jpeg',
            'bio' => 'nullable|string|min:10',
            'password' => 'nullable|string|min:6',
        ];
    }


    public function attributes()
    {
        return [
            'name' => 'نام',
            'email' => 'ایمیل',
            'username' => 'نام کاربری',
            'mobile' => 'موبایل',
            'head_line' => 'عنوان',
            'website' => 'وب سایت',
            'linkedin' => 'لینکدین',
            'facebook' => 'فیس بوک',
            'twitter' => 'توییتر',
            'youtube' => 'یوتوب',
            'instagram' => 'اینستاگرام',
            'telegram' => 'تلگرام',
            'status' => 'وضعیت',
            'image' => 'عکس پروفایل',
            'bio' => 'بیوگرافی',
            'password' => 'رمز عبور'
        ];
    }

}
