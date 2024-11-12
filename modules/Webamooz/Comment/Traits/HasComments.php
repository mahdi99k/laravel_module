<?php

namespace Webamooz\Comment\Traits;

use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Webamooz\Comment\Models\Comment;

trait HasComments
{
    use HasRelationships;  //polymorphic,relations بشناسه روابط ما
    //method_exists($value,"comments");  //One)درون آبجکت یا مادل دوره برو بگرد  Two)درون مادل بگرد ببین همچین متودی داره
    public function comments()  //Relationship One To Many + morphMany===hasMany
    {
        return $this->morphMany(Comment::class, 'commentable');  //1)Model  2)name relation (paymentable) اسم ستون درون پیمنت
    }

    public function approvedComments()
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->where('status', Comment::STATUS_APPROVED)
            ->whereNull('comment_id')  //فقط کامنت ها نمایش بده به صورت جدا
            ->orderByDesc('id')  //ار آخر نمایش بده + latest()
            ->with('comments');  //پاسخ های این کانت بردار بیار
    }
}
