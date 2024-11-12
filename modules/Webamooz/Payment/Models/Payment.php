<?php

namespace Webamooz\Payment\Models;


use Illuminate\Database\Eloquent\Model;
use Webamooz\Discount\Models\Discount;
use Webamooz\User\Models\User;

class Payment extends Model
{

    const STATUS_PENDING = 'pending';
    const STATUS_CANCELED = 'canceled';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAIL = 'fail';
    public static $statuses = [
        self::STATUS_PENDING,
        self::STATUS_CANCELED,
        self::STATUS_SUCCESS,
        self::STATUS_FAIL,
    ];

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'paymentable_id',
        'paymentable_type',
        'amount',
        'invoice_id',
        'gateway',
        'status',
        'seller_percent',
        'seller_share',
        'site_share',
    ];

    //-------------------- Relationship
    public function paymentable() //Model + able
    {
        return $this->morphTo('paymentable');  //name relation
    }

    public function discounts()  //هر دوره تعداد زیادی تخفیف(تا دو تا تخفیف سراسری و کد تخفیفی)
    {
        return $this->belongsToMany(Discount::class, 'discount_payment');  //withTimestamps -> set created_at+updated_at
//      return $this->belongsToMany(Discount::class, 'discount_payment')->withTimestamps();  //withTimestamps -> set created_at+updated_at
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, "buyer_id");
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

}
