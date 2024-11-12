<?php

namespace Webamooz\Ticket\Database\Repositories;

use Webamooz\Course\Repositories\CourseRepository;
use Webamooz\Ticket\Models\Reply;
use Webamooz\Ticket\Models\Ticket;

class ReplyRepositories
{

    public function store($ticket_id, $body, $media_id = null)
    {
        return Reply::create([
            'user_id' => auth()->user()->id,
            'ticket_id' => $ticket_id,
            'media_id' => $media_id,
            'body' => $body,
        ]);
    }

}
