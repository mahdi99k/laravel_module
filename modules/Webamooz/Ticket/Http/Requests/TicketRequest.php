<?php

namespace Webamooz\Ticket\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
{

    public function authorize()
    {
        return auth()->check() == true;
    }

    public function rules()
    {
        return [
            'title' => "required|string|min:3|max:500",
            'course' => "required|numeric|exists:courses,id",
            'body' => "required|string",
            'attachment' => "nullable|file|mimes:png,jpg,jpeg,avi,mkv,mp4,zip,rar|max:10240",  //10M -> 10240
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'عنوان تیکت',
            'course' => 'دوره',
            'body' => 'متن تیکت',
            'attachment' => 'فایل پیوست',
        ];
    }

    public function messages()
    {
        return [
            'attachment.max' => "فایل پیوست نباید بزرگتر از 10 مگابایت باشد."
        ];
    }

}
