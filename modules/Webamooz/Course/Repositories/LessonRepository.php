<?php

namespace Webamooz\Course\Repositories;

use Illuminate\Support\Str;
use Webamooz\Course\Models\Lesson;
use Webamooz\Media\Services\MediaFileService;


class LessonRepository
{

    public function store($course_id, $values)
    {
        return Lesson::create([
            'course_id' => $course_id,
            'user_id' => auth()->id(),
            'season_id' => ($values->season_id),
            'title' => $values->title,
            'slug' => $values->slug ? Str::slug($values->slug) : Str::slug($values->title),
            'time' => $values->time,
            'number' => $this->generateNumber($values->number, $course_id),
            'is_free' => $values->is_free,
            'media_id' => $values->media_id,  //id set in media_id -> $request->request->add(['media_id'
            'status' => Lesson::STATUS_OPENED,
            'confirmation_status' => Lesson::CONFIRMATION_STATUS_PENDING,
            'body' => $values->body,
        ]);
    }

    public function paginate($course_id)
    {
        return Lesson::where('course_id', $course_id)->orderBy('number')->paginate(20);
    }

    public function findById($lesson_id)
    {
        return Lesson::findOrFail($lesson_id);
    }

    public function getAcceptedLessons(int $course_id)
    {
        return Lesson::where('course_id', $course_id)
            ->where('confirmation_status', Lesson::CONFIRMATION_STATUS_ACCEPTED)->get();
    }

    public function getFirstLesson(int $course_id)  //show first lesson
    {
        return Lesson::where('course_id', $course_id)
            ->where('confirmation_status', Lesson::CONFIRMATION_STATUS_ACCEPTED)->orderBy('number', 'asc')->first();
    }

    public function getShowLesson(int $course_id, int $lesson_id)
    {
        return Lesson::where('course_id', $course_id)->where('id', $lesson_id)->first();
    }

    public function update($course_id, $lesson_id, $request)
    {
        Lesson::where('id', $lesson_id)->update([
            'season_id' => $request->season_id,
            'title' => $request->title,
            'slug' => $request->slug ? Str::slug($request->slug) : Str::slug($request->title),
            'time' => $request->time,
            'number' => $this->generateNumber($request->number, $course_id),
            'is_free' => $request->is_free,
            'media_id' => $request->media_id,  //id set in media_id -> $request->request->add(['media_id'
            'body' => $request->body,
        ]);
    }

    public function updateConfirmationStatus($lesson_id, $confirm_status)
    {
        if (is_array($lesson_id)) {
            return Lesson::query()->whereIn('id', $lesson_id)->update([
                'confirmation_status' => $confirm_status
            ]);
        }
        return Lesson::where('id', $lesson_id)->update(['confirmation_status' => $confirm_status]);
    }

    public function updateStatus($lesson_id, $status)
    {
        return Lesson::where('id', $lesson_id)->update(['status' => $status]);
    }

    public function acceptAll($course_id)
    {
        return Lesson::where('course_id', $course_id)->update([
            'confirmation_status' => Lesson::CONFIRMATION_STATUS_ACCEPTED
        ]);
    }

    /*public function acceptMultiple($lessonIds)  //use updated with -> whereIn('column' , array)
    {
        return Lesson::query()->whereIn('id', $lessonIds)->update([
            'confirmation_status' => Lesson::CONFIRMATION_STATUS_ACCEPTED
        ]);
    }

    public function rejectMultiple($lessonIds)  //use updated with -> foreach
    {
        foreach ($lessonIds as $id) {
            $lesson = $this->findById($id);
            $lesson->update([
                'confirmation_status' => Lesson::CONFIRMATION_STATUS_REJECTED
            ]);
        }
    }*/

    public function generateNumber($number, $course_id)
    {
        $courseRepository = resolve(CourseRepository::class);

        if (is_null($number)) {
            $number = $courseRepository->findById($course_id)->lessons()->orderBy('number', 'desc')->firstOrNew([])->number ?: 0;
            $number++;  //اگر ععدی موجود نباش میاد صفر در نظر میگیره و یکی اضافه میکنه میش یک + اگر موجود باش به عدد یکی اضافه مکینه اگر وارد نکنیم دستی شماره فصل
        }
        return $number;
    }

}
