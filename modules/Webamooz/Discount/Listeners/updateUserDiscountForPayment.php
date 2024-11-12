<?php

namespace Webamooz\Discount\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class updateUserDiscountForPayment
{

    public function __construct()
    {
        //
    }

    public function handle($event)  //$event -> $payment
    {
        foreach ($event->payment->discounts as $discount) {
            $discount->uses += 1;
            if (!is_null($discount->usage_limitation)) {  //اگر تعداد محدودیت افزاد نال یا نامحدود نیست بیا یکی کم کن
                $discount->usage_limitation -= 1;
            }
            $discount->save();
        }
    }
}
