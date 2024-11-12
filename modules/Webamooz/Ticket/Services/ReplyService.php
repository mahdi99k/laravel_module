<?php

namespace Webamooz\Ticket\Services;

use Illuminate\Http\UploadedFile;
use Webamooz\Media\Services\MediaFileService;
use Webamooz\Ticket\Database\Repositories\ReplyRepositories;
use Webamooz\Ticket\Database\Repositories\TicketRepositories;
use Webamooz\Ticket\Models\Ticket;

class ReplyService
{

    public static function store(Ticket $ticket, $body, $attachment)
    {
        $replyRepo = new ReplyRepositories();
        $ticketRepo = new TicketRepositories();

        $media_id = null;
        if ($attachment && ($attachment instanceof UploadedFile)) {  //($attachment instanceof UploadedFile) -> اگر فایل ما آپلود شده بود و مقداری داشت
            $media_id = MediaFileService::privateUpload($attachment)->id;
        }

        $reply = $replyRepo->store($ticket->id, $body, $media_id);
        if ($reply->user_id != $ticket->user_id) {  //اگر کسی که به تیکت پاسخ داده کاربر ادمین یا مدرس دوره بیا وضعیت تیکت بزن بسته شده
            $ticketRepo->setStatus($ticket->id, Ticket::STATUS_REPLIED);  //وقتی ادمین پاسخ تیکت میده وضعیت به پاسخ داده شده
        }else {
            $ticketRepo->setStatus($ticket->id, Ticket::STATUS_OPEN);  //وقتی باز کاربر سوالی میپرسه وضعیت به پاسخ مشتری
        }
        return $reply;
    }

}
