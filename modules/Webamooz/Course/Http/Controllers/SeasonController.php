<?php

namespace Webamooz\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Webamooz\Common\Responses\AjaxResponses;
use Webamooz\Course\Http\Requests\SeasonRequest;
use Webamooz\Course\Models\Season;
use Webamooz\Course\Repositories\CourseRepository;
use Webamooz\Course\Repositories\SeasonRepository;

class SeasonController extends Controller
{


    private $seasonRepository;

    public function __construct(SeasonRepository $seasonRepository)
    {
        $this->seasonRepository = $seasonRepository;
    }

    public function store(SeasonRequest $request, $course_id, CourseRepository $courseRepository)
    {
        $this->authorize('createSeason', $courseRepository->findById($course_id));
        $this->seasonRepository->store($request, $course_id);
        $this->newFeedback();
        return back();
    }

    public function edit($seasons_id)
    {
        $season = $this->seasonRepository->findById($seasons_id);
        $this->authorize('edit', $season);
        return view('Course::seasons.edit', compact('season'));
    }


    public function update(SeasonRequest $request, $seasons_id)
    {
        $season = $this->seasonRepository->findById($seasons_id);
        $this->authorize('edit', $season);
        $this->seasonRepository->update($seasons_id, $request);
        $this->newFeedback();
        return to_route('courses.details', $season->course_id);
    }

    public function destroy($season_id)
    {
        $season = $this->seasonRepository->findById($season_id);
        $this->authorize('delete', $season);
        $season->delete();
        return AjaxResponses::successResponse('عملیات حذف سر فصل دوره با موفقیت انجام شد');
    }


    public function accept($season_id)
    {
        $this->authorize('change_confirmation_status', Season::class);  //just chanage manage course
        if ($this->seasonRepository->updateConfirmationStatus($season_id, Season::CONFIRMATION_STATUS_ACCEPTED)) {
            return AjaxResponses::successResponse("دوره با موفقیت تایید شد");
        }
        return AjaxResponses::failedResponse("تایید دوره با خطا مواجه شد!");
    }

    public function reject($season_id)
    {
        $this->authorize('change_confirmation_status', Season::class);  //Season::class -> بر روی تمام دوره ها اعمال بشه بتونه تغییر بده
        if ($this->seasonRepository->updateConfirmationStatus($season_id, Season::CONFIRMATION_STATUS_REJECTED)) {
            return AjaxResponses::successResponse("دوره با موفقیت رد شد");
        }
        return AjaxResponses::failedResponse("رد دوره با خطا مواجه شد!");
    }

    public function lock($season_id)
    {
        $this->authorize('change_confirmation_status', Season::class);
        if ($this->seasonRepository->updateStatus($season_id, Season::STATUS_LOCKED)) {
            return AjaxResponses::successResponse("دوره با موفقیت قفل شد");
        }
        return AjaxResponses::failedResponse("قفل دوره با خطا مواجه شد!");
    }

    public function unlock($season_id)
    {
        $this->authorize('change_confirmation_status', Season::class);
        if ($this->seasonRepository->updateStatus($season_id, Season::STATUS_OPENED)) {
            return AjaxResponses::successResponse('دوره با موفقیت باز شد');
        }
        return AjaxResponses::failedResponse('باز شدن دوره با خطا مواجه شد!');
    }


    function newFeedback($heading = 'موفقیت آمیر', $text = 'عملیات با موفقیت انجام شد', $type = 'success')
    {
        $session = session()->has('feedbacks') ? session()->get('feedbacks') : [];  //هر چند تا که بسازیم سشن به اسم فیدبکز میاد صدا میزنه فراخوانی میکنه
        $session[] = ["heading" => $heading, "text" => $text, "type" => $type];  //session is array
        session()->flash('feedbacks', $session);
    }


}
