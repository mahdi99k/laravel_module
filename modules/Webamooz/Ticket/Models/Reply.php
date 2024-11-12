<?php

namespace Webamooz\Ticket\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Webamooz\Media\Models\Media;
use Webamooz\User\Models\User;

class Reply extends Model
{
    protected $table = 'ticket_replies';  //اسم مادل و جدول چون حالت استاندارد نیست باید بهش اسم جدول جداگونه بدیم + tableStandard(Model+s)
    protected $fillable = [
        'user_id',
        'ticket_id',
        'media_id',
        'body',
    ];


    //---------- Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);  //هر پاسخ میتونه برای یک تیکت باشد
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function attachmentLink()
    {
        if ($this->media_id) {
            return URL::temporarySignedRoute('media.download', now()->addMonths(4), ['media' => $this->media_id]);
        }
    }

}
