<?php

namespace Webamooz\Comment\Database\Repositories;

use Webamooz\Comment\Models\Comment;
use Webamooz\RolePermissions\Model\Permission;

class CommentRepositories
{

    private $query;

    public function __construct()
    {
        $this->query = Comment::query();
    }

    //---------- Front Site
    public function findByIdApproved($comment_id)  //ApprovedComment -> کانت ما باید تایید شده باشد تا پاسخی بتونیم بنویسیم براش
    {
        return Comment::query()->where('status', Comment::STATUS_APPROVED)->where('id', $comment_id)->first();
    }

    public function store($data)
    {
        return Comment::create([
            'user_id' => auth()->user()->id,
            'comment_id' => array_key_exists('comment_id', $data) ? $data['comment_id'] : null,
            'commentable_id' => $data['commentable_id'],
            'commentable_type' => $data['commentable_type'],
            'body' => $data['body'],
            'status' => auth()->user()->can(Permission::PERMISSION_MANAGE_COMMENTS) || auth()->user()->can(Permission::PERMISSION_TEACH)
                ? Comment::STATUS_APPROVED : Comment::STATUS_NEW,
        ]);
    }


    //---------- Panel Admin
    public function paginate($count = 10)
    {
        return Comment::query()->latest()->paginate($count);
    }

    public function paginateParents($count = 10)  //فقط کامنت ها نمایش بده نه پاسخ ها
    {
        //withCount -> نمایش تعداد یک چیزی از طریق ریلیشن
        return $this->query->whereNull('comment_id')->withCount('notApprovedComments')->latest()->paginate($count);

        /*$query = Comment::whereNull('comment_id')->withCount('notApprovedComments');
        if(! is_null($status)){
            $query->where('status' , $status);
        }
        return $query->latest()->paginate($count);*/
    }

    public function findById($comment_id)
    {
        return Comment::query()->find($comment_id);
    }

    public function updateStatus($comment_id, $status)
    {
        return Comment::query()->where('id', $comment_id)->update([
            'status' => $status
        ]);
    }

    //----- Search
    /*public function searchJoinUser()
    {
        $this->query->join('users', 'comments.user_id', 'users.id')
            ->select('comments.*', 'users.name', 'users.email');
        return $this;
    }*/

    public function searchStatus($status)
    {
        if (!is_null($status)) {
            $this->query->where('status', $status);
        }
        return $this;
    }

    public function searchBody($body)
    {
        if (!is_null($body)) {
            $this->query->where('body', 'LIKE', '%' . $body . '%');
        }
        return $this;
    }

    public function searchEmail($email)
    {
        if (!is_null($email))
            $this->query->whereHas('user', function ($q) use ($email) {
                $q->where('email', 'LIKE', '%' . $email . '%');  //کوری ما درون جدول کاربران زده میشه
            });
        return $this;
    }

    public function searchName($name)
    {
        if (!is_null($name))
            $this->query->whereHas('user', function ($q) use ($name) {
                $q->where('name', 'LIKE', '%' . $name . '%');
            });
        return $this;
    }
    //----- Search

}
