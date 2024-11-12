<?php

namespace Webamooz\Discount\Http\Controllers;

use App\Helper\Generate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Webamooz\Common\Responses\AjaxResponses;
use Webamooz\Course\Models\Course;
use Webamooz\Course\Repositories\CourseRepository;
use Webamooz\Discount\Http\Requests\DiscountRequest;
use Webamooz\Discount\Models\Discount;
use Webamooz\Discount\Repositories\DiscountRepo;
use Webamooz\Discount\Services\DiscountService;

class DiscountController extends Controller
{

    private DiscountRepo $discountRepo;

    public function __construct(DiscountRepo $discountRepo)
    {
        $this->discountRepo = $discountRepo;
    }

    public function index(CourseRepository $courseRepo)
    {
        $this->authorize('manage', Discount::class);
        $discounts = $this->discountRepo->paginate(12);
        $courses = $courseRepo->getAll(Course::CONFIRMATION_STATUS_ACCEPTED);
        return view('Discounts::index', compact('discounts', 'courses'));
    }

    public function store(DiscountRequest $request)
    {
        $this->authorize('manage', Discount::class);
        $this->discountRepo->store($request->all());  //($request->all()->array  +  $request->object
        Generate::newFeedback();
        return back();
    }

    public function edit(Discount $discount, CourseRepository $courseRepo)
    {
        $this->authorize('manage', Discount::class);
        $courses = $courseRepo->getAll(Course::CONFIRMATION_STATUS_ACCEPTED);
        return view("Discounts::edit", compact('discount', 'courses'));
    }

    public function update(DiscountRequest $request, Discount $discount)
    {
        $this->authorize('manage', Discount::class);
        $this->discountRepo->update($discount, $request->all());
        Generate::newFeedback();
        return to_route('discounts.index');
    }

    public function destroy(Discount $discount)
    {
        $this->authorize('manage', Discount::class);
        $discount->delete();
        return AjaxResponses::successResponse("تخفیف با موفقیت حذف شد");
    }

    //---------- check code discount in front singleCourse
    public function check($code, Course $course)
    {
        $discount = $this->discountRepo->getValidDiscountByCode($code, $course->id);

        if ($discount) {
            $discountAmount = DiscountService::calculateDiscountAmount($course->getFinalPrice(), $discount->percent);  //مبلغ تخفیف
            //$payableAmount -> اگر دوره ای تخفیفی سراسری داشت و ما کد تخفیفی زدیم میاد از مقدار تخفیفی سراسری خورده اعمال مکینه نه قسمت اولیه دوره
            $payableAmount = $course->getFinalPrice() - $discountAmount;  //قابل پرداخت
            $discountPercent = $discount->percent;  //درصد تخفیف

            $response = [
                "status" => "valid",
                "payableAmount" => $payableAmount,
                "discountAmount" => $discountAmount,
                "discountPercent" => $discountPercent,
            ];
            return response()->json($response);

        } else {
            return response()->json(["status" => "invalid"])->setStatusCode(422);
        }
    }

}
