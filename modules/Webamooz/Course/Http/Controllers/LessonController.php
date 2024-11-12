<?php

namespace Webamooz\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webamooz\Common\Responses\AjaxResponses;
use Webamooz\Course\Http\Requests\LessonRequest;
use Webamooz\Course\Models\Course;
use Webamooz\Course\Models\Lesson;
use Webamooz\Course\Repositories\CourseRepository;
use Webamooz\Course\Repositories\LessonRepository;
use Webamooz\Course\Repositories\SeasonRepository;
use Webamooz\Media\Services\DefaultFileService;
use Webamooz\Media\Services\MediaFileService;

class LessonController extends Controller
{

    private $lessonRepository;

    public function __construct(LessonRepository $lessonRepository)
    {
        $this->lessonRepository = $lessonRepository;
    }

    public function create($course_id, SeasonRepository $seasonRepository, CourseRepository $courseRepository)
    {
        $course = $courseRepository->findById($course_id);
        $this->authorize('createLesson', $course);
        $seasons = $seasonRepository->getCourseSeasons($course_id);
        $course = $courseRepository->findById($course_id);
        return view('Course::lessons.create', compact('seasons', 'course'));
    }

    public function store($course_id, LessonRequest $request, CourseRepository $courseRepository)
    {
        $course = $courseRepository->findById($course_id);
        $this->authorize('store', $course);
        $request->request->add(['media_id' => MediaFileService::privateUpload($request->file('lesson_file'))->id]);
        $this->lessonRepository->store($course_id, $request);
        $this->newFeedback();
        return to_route('courses.details', $course_id);
    }

    public function edit($course_id, $lesson_id, SeasonRepository $seasonRepository, CourseRepository $courseRepository)
    {
        $lesson = $this->lessonRepository->findById($lesson_id);
        $course = $courseRepository->findById($course_id);
        $this->authorize('edit', $lesson);
        $seasons = $seasonRepository->getCourseSeasons($course_id);
        return view('Course::lessons.edit', compact('lesson', 'seasons', 'course'));
    }

    public function update(LessonRequest $request, $course_id, $lesson_id)
    {
        $lesson = $this->lessonRepository->findById($lesson_id);
        $this->authorize('edit', $lesson);

        if ($request->hasFile('lesson_file')) {  //اگر فایلی کلیک کرد + اگر اون درس فایل ویدیویی یا زیپ دارد
            if ($lesson->media) {
                $lesson->media->delete();
            }
            $request->request->add(['media_id' => MediaFileService::privateUpload($request->file('lesson_file'))->id]);
        } else {
            $request->request->add(['media_id' => $lesson->lesson_file]);  //اگر ویدیویی وجود نداشت بیا همون ویدیو قبلی بگیر
        }

        $this->lessonRepository->update($course_id, $lesson_id, $request);
        $this->newFeedback();
        return to_route('courses.details', $course_id);
    }

    public function destroy($course_id, $lesson_id)
    {
        $lesson = $this->lessonRepository->findById($lesson_id);
        $this->authorize('delete', $lesson);
        if ($lesson->media) {
            $lesson->media->delete();  //protected static function booted() { static::deleting(function($media)) MediaFileService::delete($media)  }
        }
        $lesson->delete();
        return AjaxResponses::successResponse('درس شما با موفقیت حذف شد');
    }

    public function destroyMultiple(Request $request)
    {
        $lessonIds = explode(',', $request->lesson_ids);  //string to array
        foreach ($lessonIds as $id) {
            $lesson = $this->lessonRepository->findById($id);
            $this->authorize('delete', $lesson);
            if ($lesson->media) {
                $lesson->media->delete();  //protected static function booted() { static::deleting(function($media)) MediaFileService::delete($media)  }
            }
            $lesson->delete();
        }
        $this->newFeedback();
        return back();
    }


    public function acceptAll($course_id)
    {
        $this->authorize('manage', Course::class);
        $this->lessonRepository->acceptAll($course_id);
        $this->newFeedback();
        return back();
    }

    public function acceptMultiple(Request $request, $course_id)
    {
        $this->authorize('manage', Course::class);
        $lessonIds = explode(',', $request->lesson_ids);
        $this->lessonRepository->updateConfirmationStatus($lessonIds, Lesson::CONFIRMATION_STATUS_ACCEPTED);
        $this->newFeedback();
        return back();
    }

    public function rejectMultiple(Request $request, $course_id)
    {
        $this->authorize('manage', Course::class);
        $lessonIds = explode(',', $request->lesson_ids);
        $this->lessonRepository->updateConfirmationStatus($lessonIds, Lesson::CONFIRMATION_STATUS_REJECTED);
        $this->newFeedback();
        return back();
    }

    public function accept($lesson_id)
    {
        $this->authorize('manage', Course::class);
        $this->authorize('manage', Course::class);
        $this->lessonRepository->updateConfirmationStatus($lesson_id, Lesson::CONFIRMATION_STATUS_ACCEPTED);
        return AjaxResponses::successResponse("عملیات تایید درس با موفقیت انجام شد");
    }

    public function reject($lesson_id)
    {
        $this->authorize('manage', Course::class);
        $this->lessonRepository->updateConfirmationStatus($lesson_id, Lesson::CONFIRMATION_STATUS_REJECTED);
        return AjaxResponses::successResponse("عملیات رد شدن درس با موفقیت انجام شد");
    }

    public function lock($lesson_id)
    {
        $this->authorize('manage', Course::class);
        $this->lessonRepository->updateStatus($lesson_id, Lesson::STATUS_LOCKED);
        return AjaxResponses::successResponse("عملیات قفل کردن درس با موفقیت انجام شد");
    }

    public function unlock($lesson_id)
    {
        $this->authorize('manage', Course::class);
        $this->lessonRepository->updateStatus($lesson_id, Lesson::STATUS_OPENED);
        return AjaxResponses::successResponse("عملیات باز کردن درس با موفقیت انجام شد");
    }

    function newFeedback($heading = 'موفقیت آمیر', $text = 'عملیات با موفقیت انجام شد', $type = 'success')
    {
        $session = session()->has('feedbacks') ? session()->get('feedbacks') : [];  //هر چند تا که بسازیم سشن به اسم فیدبکز میاد صدا میزنه فراخوانی میکنه
        $session[] = ["heading" => $heading, "text" => $text, "type" => $type];  //session is array
        session()->flash('feedbacks', $session);
    }

}
