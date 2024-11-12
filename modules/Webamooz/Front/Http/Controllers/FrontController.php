<?php

namespace Webamooz\Front\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Webamooz\Course\Models\Course;
use Webamooz\Course\Repositories\CourseRepository;
use Webamooz\Course\Repositories\LessonRepository;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\Slider\Database\Repositories\SlideRepositories;
use Webamooz\Slider\Models\Slide;
use Webamooz\User\Models\User;

class FrontController extends Controller
{

    public function index()
    {
        return view('Front::index');
    }

    public function singleCourse($slug, CourseRepository $courseRepository, LessonRepository $lessonRepository)
    {
        //Str::after($slug , 'c-') -> 1-course-laravel-11  //delete(c-)
        //get id -> 1)get slug(subject) 2)search بردار نمایش بده c- برو هرچی بعد از  +  befor -> هرچی قبل اولین دش بر دار نمایش بده که آیدی کاربر
        $course_id = $this->extractId($slug, 'c');
        $course = $courseRepository->findById($course_id);
        $lessons = $lessonRepository->getAcceptedLessons($course_id);

        if (request()->lesson) {  //اگر درسی انتخاب بکنه اون نمایش میده ورگنه درس شماره یک نمایش میده
            $lesson = $lessonRepository->getShowLesson($course_id, $this->extractId(request()->lesson, 'l'));
        } else {
            $lesson = $lessonRepository->getFirstLesson($course_id);
        }
        return view('Front::singleCourse', compact('course', 'lessons', 'lesson'));
    }

    public function extractId($slug, $key)
    {
        return Str::before(Str::after($slug, $key . '-'), '-');
    }

    public function singleTutors($username)
    {
        $tutor = User::permission(Permission::PERMISSION_TEACH)->where('username', $username)->first();
        return view('Front::tutors', compact('tutor'));
    }

}
