<?php

namespace Webamooz\Media\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webamooz\Media\Services\MediaFileService;
use Webamooz\User\Models\User;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'files',  //json
        'type',  //enum('type', ['image', 'video', 'audio', 'zip', 'doc']
        'filename',
        'is_private',
    ];

    protected $casts = [
        'files' => 'json',  //json === object is same
    ];


    //---------- Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getThumbAttribute(): string
    {
        return MediaFileService::thumb($this);  //$this === $media
    }

    public function getUrl($quality = 'original'): string
    {
        return "/storage/" . $this->files[$quality];
    }

    protected static function booted()  //next run all boot
    {
        static::deleting(function ($media) {  //before run -> course->media->delete() فقط وقتی میایم مدیا صدا میزنیم اونم متود دلیت
            MediaFileService::delete($media);
        });
    }

}
