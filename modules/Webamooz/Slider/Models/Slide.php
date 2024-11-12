<?php

namespace Webamooz\Slider\Models;

use Illuminate\Database\Eloquent\Model;
use Webamooz\Media\Models\Media;
use Webamooz\User\Models\User;

class Slide extends Model
{

    /*
    const STATUS_ENABLE = 'enable';
    const STATUS_DISABLE = 'disable';
    public static array $statuses = [
        self::STATUS_ENABLE,
        self::STATUS_DISABLE
    ];
    */

    protected $fillable = [
        'user_id',
        'media_id',
        'priority',
        'link',
        'status',
    ];

    //---------- Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

}
