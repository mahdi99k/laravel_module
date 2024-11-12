<?php

namespace Webamooz\Course\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Webamooz\Course\Models\Course;
use Webamooz\Course\Models\Lesson;
use Webamooz\Course\Rules\ValidSeason;
use Webamooz\Media\Services\MediaFileService;

class LessonRequest extends FormRequest
{

    public function authorize()
    {
        return auth()->check() == true;
    }

    public function rules()
    {
        $rules = [
            'title' => 'required|min:3|max:200',
            'slug' => 'nullable|min:3|max:200',
            'number' => 'nullable|numeric',
            'time' => 'required|numeric|min:0|max:255',
            'season_id' => [new ValidSeason()],
            'is_free' => 'required|boolean',
            'lesson_file' => 'required|file|mimes:' . MediaFileService::getExtensions(),  //"png,jpg,jpeg,svg,avi,mp4,mkv,zip,rar,tar"
            'body' => 'nullable|string',
        ];
        if (request()->method == "PATCH") {  //request updated
            $rules['lesson_file'] = 'nullable|file|mimes:' . MediaFileService::getExtensions();  //"png,jpg,jpeg,svg,avi,mp4,mkv,zip,rar,tar"
        }

        return $rules;
    }


    public function attributes()
    {
        return [
            'course_id' => 'دوره',
            'season_id' => 'سرفصل',
            'user_id' => 'کاربر',
            'media_id' => 'فایل',
            'title' => 'عنوان درس',
            'slug' => 'عنوان انگلیسی درس',
            'number' => 'شماره درس',
            'time' => 'مدت زمان درس',
            'is_free' => 'رایگان',
            'lesson_file' => 'فایل درس',
            'body' => 'توضیحات درس',
            'confirmation_status' => 'تایید وضعیت',
            'status' => 'وضعیت'
        ];
    }

}
