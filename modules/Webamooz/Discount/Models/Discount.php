<?php

namespace Webamooz\Discount\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webamooz\Course\Models\Course;
use Webamooz\Payment\Models\Payment;

class Discount extends Model
{
    use HasFactory;

    const TYPE_ALL = 'all';
    const TYPE_SPECIAL = 'special';
    public static array $types = [
        self::TYPE_ALL,
        self::TYPE_SPECIAL,
    ];

    protected $fillable = [
        'user_id',
        'code',
        'percent',
        'usage_limitation',
        'expire_at',
        'link',
        'description',
        'type',
        'uses',
        'discount_id',
        'discountable_id',
        'discountable_type',
    ];

    protected $casts = [
        "expire_at" => "datetime"
    ];

    //-------------------- Relationship
    public function courses()
    {
        return $this->morphedByMany(Course::class, 'discountable');  //discountable = name relation -> Model + able
    }

    public function payments()  //هر تخفیف برای تعداد زیادی دوره(تا دو تا تخفیف سراسری و کد تخفیفی)
    {
        return $this->belongsToMany(Payment::class, 'discount_payment');
    }

}
