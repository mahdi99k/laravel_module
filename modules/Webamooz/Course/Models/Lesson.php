<?php

namespace Webamooz\Course\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Webamooz\Media\Models\Media;
use Webamooz\Media\Services\MediaFileService;
use Webamooz\User\Models\User;

class Lesson extends Model
{

    const CONFIRMATION_STATUS_ACCEPTED = 'accepted';
    const CONFIRMATION_STATUS_REJECTED = 'rejected';
    const CONFIRMATION_STATUS_PENDING = 'pending';
    public static array $confirmationStatuses = [self::CONFIRMATION_STATUS_ACCEPTED, self::CONFIRMATION_STATUS_REJECTED, self::CONFIRMATION_STATUS_PENDING];

    const STATUS_OPENED = 'opened';
    const STATUS_LOCKED = 'locked';
    public static array $statuses = [self::STATUS_OPENED, self::STATUS_LOCKED];

    protected $fillable = [
        'course_id',
        'season_id',
        'user_id',
        'media_id',
        'lesson_file',
        'title',
        'slug',
        'is_free',
        'body',
        'time',
        'number',
        'confirmation_status',
        'status',
    ];

    //-------------------- Relationship
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function getConfirmationStatusCssClass()
    {
        if ($this->confirmation_status == self::CONFIRMATION_STATUS_ACCEPTED) return "text-success_custom";
        elseif ($this->confirmation_status == self::CONFIRMATION_STATUS_REJECTED) return "text-danger_custom";
    }

    public function path()
    {
        return $this->course->path() . '?lesson=l-' . $this->id . '-' . $this->slug;  //?lesson=l-2-route-map
    }

    public function downloadLink()
    {
        if ($this->media()) {  //اگر مدیا در جدول درس ها ثبت شده آیدیش بیا لینک دانلود بساز
            return URL::temporarySignedRoute('media.download', now()->addDay(), ['media' => $this->media_id]);
        }
    }

}
