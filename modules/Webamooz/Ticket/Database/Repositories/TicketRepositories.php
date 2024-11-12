<?php

namespace Webamooz\Ticket\Database\Repositories;

use Illuminate\Database\Eloquent\Model;
use Webamooz\Course\Repositories\CourseRepository;
use Webamooz\Ticket\Models\Reply;
use Webamooz\Ticket\Models\Ticket;

class TicketRepositories
{

    private $query;

    public function __construct()
    {
        $this->query = Ticket::query();
    }

    public function paginateAll($count = 10, $user_id = null)
    {
        $query = Ticket::query();
        if ($user_id) {
            return $query->where('user_id', $user_id)->paginate($count);
        }

        return $query->latest()->paginate($count);  //اگر آیدی کاربر نداشت یعنی برای ادمین یا مدیریت تیکت باید نمایش بدیم همه
    }


    //---------- Search
    public function joinUsers()
    {
        //join -> One)روی چه جدولی میخوای جوین بزنی  two)مقدار جدولی که کوری زدیم ازش تیکت به کاربر کدوم ستون  three)مقدار ستون جدول جوین شده
        $this->query->join("users", 'tickets.user_id', 'users.id')->select('tickets.*', 'users.email', 'users.name');
        return $this;
    }

    public function searchTitle($title)
    {
        if (!is_null($title))
            $this->query->where("tickets.title", "LIKE", "%" . $title . "%");
        return $this;
    }

    public function searchEmail($email)
    {
        if (!is_null($email))
            $this->query->where('users.email', 'LIKE', '%' . $email . '%');
        return $this;  //مه میریزه درون اون کوری متفیر پرایوت و پشت هم وصل میشه دستورات کوری
    }

    public function searchName($name)
    {
        if (!is_null($name))
            $this->query->where('users.name', 'LIKE', '%' . $name . '%');
        return $this;
    }

    public function searchDate($date)
    {
        if (!is_null($date)) {
            $this->query->whereDate('tickets.updated_at', '=', $date);
        }
        return $this;
    }

    public function searchStatus($status)
    {
        if (!is_null($status)) {
            $this->query->where('tickets.status', '=', $status);
        }
        return $this;
    }

    public function paginate($count = 10)
    {
        return $this->query->latest()->paginate($count);
    }
    //---------- Search

    public function findById($ticket_id)
    {
        return Ticket::query()->where('id', $ticket_id)->first();
    }

    public function findByIdWithReply($ticket_id)
    {
        return Ticket::query()->with('replies')->findOrFail($ticket_id);  //تیکت همراه ریپلای ها
    }

    public function store($request): Model
    {
        $course = (new CourseRepository())->findById($request->course);  //object Course
        return Ticket::create([
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'ticketable_id' => $request->course,  //Relationship One To Many
            'ticketable_type' => get_class($course),  //object + Relationship One To Many
        ]);
    }

    public function setStatus($ticket_id, $status)
    {
        return Ticket::query()->where("id", $ticket_id)->update([
            'status' => $status,
        ]);
    }

    public function delete(Ticket $ticket)
    {
//      $hasAttachmentsReply = $ticket->replies()->whereNotNull('media_id')->get();
        $hasAttachmentsReply = Reply::query()->where('ticket_id', $ticket->id)->whereNotNull('media_id')->with('media')->get();
        foreach ($hasAttachmentsReply as $reply) {
            $reply->media->delete();
        }
        $ticket->delete();
    }

}
