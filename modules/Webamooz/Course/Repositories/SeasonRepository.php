<?php

namespace Webamooz\Course\Repositories;

use Webamooz\Course\Models\Course;
use Webamooz\Course\Models\Season;
use Webamooz\Media\Services\MediaFileService;


class SeasonRepository
{

    public function getCourseSeasons($course_id)
    {
        return Season::where('course_id', $course_id)
            ->where('confirmation_status', Season::CONFIRMATION_STATUS_ACCEPTED)
            ->orderBy('number')->get();
    }

    public function findByIdAndCourseId($season_id, $course_id)
    {
        return Season::where('course_id', $course_id)->where('id', $season_id)->first();
    }

    public function store($request, $course_id)
    {
        return Season::create([
            'course_id' => $course_id,
            'user_id' => auth()->id(),
            'title' => $request->title,
            'number' => $this->generateNumber($request->number, $course_id),
            'confirmation_status' => Season::CONFIRMATION_STATUS_PENDING,
            'status' => Season::STATUS_OPENED,
        ]);
    }

    public function findById($season_id)
    {
        return Season::findOrFail($season_id);
    }

    public function update($season_id, $request)
    {
        Season::where('id', $season_id)->update([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'number' => $this->generateNumber($request->number, $season_id),
        ]);
    }

    public function updateConfirmationStatus($season_id, $confirm_status)
    {
        return Season::where('id', $season_id)->update(['confirmation_status' => $confirm_status]);
    }

    public function updateStatus($season_id, $status)
    {
        return Season::where('id', $season_id)->update(['status' => $status]);
    }

    public function generateNumber($number, $course_id)
    {
        $courseRepository = resolve(CourseRepository::class);

        if (is_null($number)) {
            $number = $courseRepository->findById($course_id)->seasons()->orderBy('number', 'desc')->firstOrNew([])->number ?: 0;
            $number++;  //اگر ععدی موجود نباش میاد صفر در نظر میگیره و یکی اضافه میکنه میش یک + اگر موجود باش به عدد یکی اضافه مکینه اگر وارد نکنیم دستی شماره فصل
        }
        return $number;
    }

}
