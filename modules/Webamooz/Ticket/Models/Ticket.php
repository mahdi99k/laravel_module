<?php

namespace Webamooz\Ticket\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webamooz\Course\Models\Course;
use Webamooz\Media\Models\Media;
use Webamooz\User\Models\User;

class Ticket extends Model
{
    use HasFactory;

    const STATUS_OPEN = 'open';
    const STATUS_CLOSE = 'close';
    const STATUS_PENDING = 'pending';
    const STATUS_REPLIED = 'replied';
    public static array $statuses = [
        self::STATUS_OPEN,
        self::STATUS_CLOSE,
        self::STATUS_PENDING,
        self::STATUS_REPLIED,
    ];

    protected $fillable = [
        //---------- tickets
        'user_id',
        'ticketable_id',
        'ticketable_type',
        'title',
        'status',
    ];

    //---------- Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticketable()  //Relationship One To Many
    {
        return $this->morphTo();  //یک نام میدیم برای پلی مورفی (اختیاری) + morphTo===belongsTo
    }

    public function replies()  //هر کامنت متعلق به یک وبلاگ و هر وبلاگ کلی کامنت داره + هر پاسخ متعلق به یک تیکت و هر تیکت کلی پاسخ دارد
    {
        return $this->hasMany(Reply::class);  //هر تیکت میتونه تعداد زیادی پاسخ داشته باشد
    }

}
