<?php

namespace Webamooz\Comment\Http\Controllers;

use App\Helper\Generate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Webamooz\Comment\Database\Repositories\CommentRepositories;
use Webamooz\Comment\Events\CommentApprovedEvent;
use Webamooz\Comment\Events\CommentRejectedEvent;
use Webamooz\Comment\Events\CommentSubmittedEvent;
use Webamooz\Comment\Http\Requests\CommentRequest;
use Webamooz\Comment\Models\Comment;
use Webamooz\Common\Responses\AjaxResponses;
use Webamooz\RolePermissions\Model\Permission;

class CommentController extends Controller
{

    private CommentRepositories $commentRepo;

    public function __construct(CommentRepositories $commentRepositories)
    {
        $this->commentRepo = $commentRepositories;
    }

    //-------------------- Front Site
    public function store(CommentRequest $request)
    {
        //Webamooz\Course\Models\Course::findOrFail(1)
        //$commentable = $request->commentable_type::findOrFail($request->commentable_id);  //از طریق مادل گرفتیم دیتا دوره مد نظر
        $comment = $this->commentRepo->store($request->all());
        event(new CommentSubmittedEvent($comment));
        Generate::newFeedback("عملیات موفقیت آمیر", "دیدگاه شما با موفقیت ثبت شد");
        return back();
    }


    //-------------------- Panel Admin
    public function index(Request $req)
    {
        $this->authorize('index', Comment::class);

        $comments = $this->commentRepo
//          ->searchJoinUser()
            ->searchStatus($req->status)
            ->searchBody($req->body)
            ->searchEmail($req->email)
            ->searchName($req->name)
            ->paginateParents(12);  //paginateParents -> فقط نمایش کامنت ها

        if (!auth()->user()->hasAnyPermission(Permission::PERMISSION_MANAGE_COMMENTS, Permission::PERMISSION_SUPER_ADMIN)) {
            /*$comments->query()->where('id' , '=' , 4)->whereHasMorph("commentable", [Course::class] , function ($query) {
                return $query->where("teacher_id", auth()->id());
            });*/
            $comments = Comment::query()
                ->whereHas('commentable', function ($q) {
                    return $q->where('teacher_id', auth()->user()->id);
                })->whereNull('comment_id')
                ->where('status', Comment::STATUS_APPROVED)
                ->withCount('notApprovedComments')
                ->latest()
                ->paginate();
        }
        return view('Comments::index', compact('comments'));
    }

    public function show($comment_id)
    {
        $comment = Comment::query()->where('id', $comment_id)->with(['commentable', 'comments', 'user'])->first();
        $this->authorize('view', $comment);
        return view('Comments::show', compact('comment'));
    }

    public function destroy($comment_id)
    {
        $this->authorize('manage', Comment::class);
        $comment = $this->commentRepo->findById($comment_id);
        $comment->delete();
        return AjaxResponses::successResponse('دیدگاه با موفقیت حذف شد');
    }

    public function reject($comment_id)
    {
        $this->authorize('manage', Comment::class);
        $comment = $this->commentRepo->findById($comment_id);
        if ($this->commentRepo->updateStatus($comment_id, Comment::STATUS_REJECTED)) {
            CommentRejectedEvent::dispatch($comment);
            return AjaxResponses::successResponse('دیدگاه با موفقیت رد شد');
        }
        return AjaxResponses::failedResponse('دیدگاه با خطا مواجه شد');
    }

    public function accept($comment_id)
    {
        $this->authorize('manage', Comment::class);
        $comment = $this->commentRepo->findById($comment_id);
        if ($this->commentRepo->updateStatus($comment_id, Comment::STATUS_APPROVED)) {
            CommentApprovedEvent::dispatch($comment);  //event(new CommentApprovedEvent($comment))
            return AjaxResponses::successResponse('دیدگاه با موفقییت تایید شد');
        }
        return AjaxResponses::failedResponse('دیدیگاه با خطا مواجه شد');
    }

}
