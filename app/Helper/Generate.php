<?php

namespace App\Helper;

use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

class Generate
{

    public static function newFeedback($heading = 'موفقیت آمیر', $text = 'عملیات با موفقیت انجام شد', $type = 'success')
    {
        $session = session()->has('feedbacks') ? session()->get('feedbacks') : [];
        $session[] = ["heading" => $heading, "text" => $text, "type" => $type];
        session()->flash('feedbacks', $session);
    }

    public static function getDateJaliliToMiadi($date, $format = "Y/m/d"): ?\Carbon\Carbon
    {
        return $date ? Jalalian::fromFormat($format, $date)->toCarbon() : null;
    }

    public static function getDateMiadiToJalili($date, $format = "Y-m-d")  //Y-m-d -> read from database
    {
        return Jalalian::fromCarbon(Carbon::createFromFormat($format, $date))->format($format);
    }

    public static function createFromCarbon(Carbon $carbon): Jalalian
    {
        return Jalalian::fromCarbon($carbon);
    }

}
