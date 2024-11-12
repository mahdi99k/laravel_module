<?php

namespace Webamooz\Ticket\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReplyRequest extends FormRequest
{

    public function authorize()
    {
        return auth()->check() == true;
    }

    public function rules()
    {
        return [
            'body' => "required|string",
            'attachment' => "nullable|file|mimes:png,jpg,jpeg,avi,mkv,mp4,zip,rar|max:10240",  //10M -> 10240
        ];
    }

    public function attributes()
    {
        return [
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
