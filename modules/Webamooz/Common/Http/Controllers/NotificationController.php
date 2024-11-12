<?php

namespace Webamooz\Common\Http\Controllers;

use App\Helper\Generate;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{

    public function markAllAsRead()
    {
//      auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        auth()->user()->unreadNotifications->markAsRead();  //marjAsRead -> column(read_at) = now()
        Generate::newFeedback('موفقیت آمیر', 'همه دیدگاه های شما با موفقیت خوانده شد');
        return back();
    }

}
