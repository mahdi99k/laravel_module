<?php

namespace Webamooz\Course\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Webamooz\Course\Models\Course;
use Webamooz\Course\Repositories\CourseRepository;

class RegisterUserInTheCourse
{

    public function __construct()
    {
        //
    }

    public function handle($event)  //handle($event) -> property constructor PaymentWasSuccessful Event دسترسی به مقدار کانتراکتو رپاس دادیم مثل پیمنت
    {
        if ($event->payment->paymentable_type == Course::class) {  //بیا مقدار پیمنتی که میفرستیم یک آبجکت کامل بگیر بعد بیا ببین اگر نوع کلاس از نوع دوره این کارو بکن
            resolve(CourseRepository::class)->addStudentToCourse($event->payment->paymentable , $event->payment->buyer_id);
        }
    }
}
