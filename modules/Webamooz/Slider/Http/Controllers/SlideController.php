<?php

namespace Webamooz\Slider\Http\Controllers;

use App\Helper\Generate;
use App\Http\Controllers\Controller;
use Webamooz\Common\Responses\AjaxResponses;
use Webamooz\Media\Services\MediaFileService;
use Webamooz\Slider\Http\Requests\SlideRequest;
use Illuminate\Http\Request;
use Webamooz\Slider\Database\Repositories\SlideRepositories;
use Webamooz\Slider\Models\Slide;

class SlideController extends Controller
{

    public SlideRepositories $slideRepo;

    public function __construct(SlideRepositories $slideRepo)
    {
        $this->slideRepo = $slideRepo;
    }

    public function index()
    {
        $this->authorize('manage', Slide::class);
        $slides = $this->slideRepo->paginate(12);
        return view('Slides::index', ['slides' => $slides]);
    }

    public function store(SlideRequest $request)
    {
        $this->authorize('manage', Slide::class);
        if ($request->has('image')) {
            $media_id = MediaFileService::publicUpload($request->file('image'))->id;
        }
        $this->slideRepo->store($request, $media_id);
        Generate::newFeedback('موفقیت آمیر', 'عملیاد ایجاد اسلاید با موفقیت انجام شد');
        return to_route('slides.index');
    }

    public function edit(Slide $slide)
    {
        $this->authorize('manage', Slide::class);
        return view('Slides::edit', compact('slide'));
    }

    public function update(SlideRequest $request, $slide_id)
    {
        $this->authorize('manage', Slide::class);
        $slide = $this->slideRepo->findById($slide_id);
        if ($request->has('image')) {
            $media_id = MediaFileService::publicUpload($request->file('image'))->id;
            if ($slide->media) {
                $slide->media->delete();
            }
        } else {
            $media_id = $slide->media_id;
        }
        $this->slideRepo->update($request, $slide_id, $media_id);
        Generate::newFeedback('موفقیت آمیر', 'عملیات ویرایش اسلاید با موفقیت انجام شد');
        return to_route('slides.index');
    }

    public function destroy(Slide $slide)
    {
        $this->authorize('manage', Slide::class);
        if ($slide->media) {
            $slide->media()->delete();
        }
        $slide->delete();
        return AjaxResponses::successResponse('اسلاید با موفقیت حذف شد');
    }

}
