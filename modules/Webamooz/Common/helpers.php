<?php

use Morilog\Jalali\Jalalian;

/*function newFeedback($heading = 'موفقیت آمیر', $text = 'عملیات با موفقیت انجام شد', $type = 'success')
{
    $session = session()->has('feedbacks') ? session()->get('feedbacks') : [];
    $session[] = ["heading" => $heading, "text" => $text, "type" => $type];
    session()->flash('feedbacks', $session);
}

function generateDateJaliliToMiadi($date, $format = "Y/m/d"): ?\Carbon\Carbon
{
    return $date ? Jalalian::fromFormat($format, $date)->toCarbon() : null;
}*/
