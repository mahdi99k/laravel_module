<?php

namespace Webamooz\Comment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;
use Webamooz\Course\Models\Course;
use Webamooz\User\Models\User;

class Comment extends Model
{
    use HasFactory;

    const STATUS_NEW = 'new';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public static array $statuses = [
        self::STATUS_NEW,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
    ];

    protected $fillable = [
        'user_id',
        'comment_id',
        'commentable_id',
        'commentable_type',
        'body',
        'status',
    ];

    //---------- Methods
    public function getStatusCssClass()
    {
        if ($this->status == self::STATUS_NEW) return 'text-info_custom';
        elseif ($this->status == self::STATUS_APPROVED) return 'text-success_custom';
        elseif ($this->status == self::STATUS_REJECTED) return 'text-danger_custom';
    }

    //---------- Relationship
    public function commentable()  //commaneable -> اسمی جز این اسم درون دیتابیس گذاشتیم بزاریم باید در مقدار اول (مورف تو) این نام قرار بدیم که بشناسه
    {
        return $this->morphTo();  //One To Many + morphTo===belongsTo
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notApprovedComments()  //پاسخ خای جدیدی یک کامنت
    {
        return $this->hasMany(Comment::class)->where('status', self::STATUS_NEW);  //فقط پاسخ ها اگر وضعیت جدیدی داشت
    }

    //---------- Relationship Comment روابط خود کامنت
    public function comment()  //parent(comment)
    {
        return $this->belongsTo(Comment::class);
    }

    public function comments()  //child(replies)
    {
        return $this->hasMany(Comment::class);
    }
}
