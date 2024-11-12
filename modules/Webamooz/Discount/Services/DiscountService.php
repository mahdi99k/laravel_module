<?php

namespace Webamooz\Discount\Services;

class DiscountService
{

    public static function calculateDiscountAmount($total, $percent)  //$total -> price Course  +  $percent -> discount
    {
        return $total * ((float)('0.' . $percent));
    }

}
