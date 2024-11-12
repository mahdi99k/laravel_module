<?php

namespace Webamooz\Ticket\Http\Controllers;

use App\Helper\Generate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Webamooz\Common\Responses\AjaxResponses;
use Webamooz\Course\Models\Course;
use Webamooz\Course\Repositories\CourseRepository;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\Ticket\Database\Repositories\TicketRepositories;
use Webamooz\Ticket\Http\Requests\ReplyRequest;
use Webamooz\Ticket\Http\Requests\TicketRequest;
use Webamooz\Ticket\Models\Reply;
use Webamooz\Ticket\Models\Ticket;
use Webamooz\Ticket\Services\ReplyService;

class TicketController extends Controller
{

    private $ticketRepo;

    public function __construct(TicketRepositories $ticketRepositories)
    {
        $this->ticketRepo = $ticketRepositories;
    }

    public function index(Request $req)
    {
        $tickets = $this->ticketRepo->paginateAll(12, auth()->id());

        if (auth()->user()->hasAnyPermission([Permission::PERMISSION_MANAGE_TICKETS, Permission::PERMISSION_SUPER_ADMIN])) {
            $tickets = $this->ticketRepo
                ->joinUsers()
                ->searchTitle($req->title)
                ->searchEmail($req->email)
                ->searchName($req->name)
                ->searchDate(Generate::getDateJaliliToMiadi($req->date))
                ->searchStatus($req->status)
                ->paginate(12);
        }
        return view("Tickets::index", compact('tickets'));
    }

    public function create(CourseRepository $courseRepo)
    {
        $courses = $courseRepo->getAll(Course::CONFIRMATION_STATUS_ACCEPTED);
        return view('Tickets::create', compact('courses'));
    }

    public function store(TicketRequest $request)
    {
        $ticket = $this->ticketRepo->store($request);
        //----- reply
        ReplyService::store($ticket, $request->body, $request->attachment);
        Generate::newFeedback();
        return to_route('tickets.index');
    }

    public function show($ticket_id)
    {
        $ticket = $this->ticketRepo->findByIdWithReply($ticket_id);
        $this->authorize('show', $ticket);  //ادمین سایت یا پرمیژن مدیریت تیکت ها + کسی که این تیکت ساخته و آیدیش یکی
        return view('Tickets::show', compact('ticket'));
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);
        $this->ticketRepo->delete($ticket);
        return AjaxResponses::successResponse("تیکت با موفقیت حذف شد");
    }


    //---------- Method Store reply
    public function reply(ReplyRequest $request, Ticket $ticket)
    {
        $this->authorize('show', $ticket);  //ادمین سایت یا پرمیژن مدیریت تیکت ها + کسی که این تیکت ساخته و آیدیش یکی
        ReplyService::store($ticket, $request->body, $request->attachment);
        Generate::newFeedback();
        return to_route('tickets.show', $ticket->id);
    }

    public function close(Ticket $ticket)
    {
        $this->authorize('show', $ticket);  //ادمین سایت یا پرمیژن مدیریت تیکت ها + کسی که این تیکت ساخته و آیدیش یکی
        $this->ticketRepo->setStatus($ticket->id, Ticket::STATUS_CLOSE);
        Generate::newFeedback();
        return to_route('tickets.index');
    }

}
