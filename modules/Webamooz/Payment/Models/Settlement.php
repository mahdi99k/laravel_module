<?php

namespace Webamooz\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webamooz\User\Models\User;

class Settlement extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_SETTLED = 'settled';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELED = 'canceled';

    public static array $statuses = [
        self::STATUS_PENDING,
        self::STATUS_SETTLED,
        self::STATUS_REJECTED,
        self::STATUS_CANCELED,
    ];

    protected $fillable = [
        'user_id',
        'transaction_id',
        'from',
        'to',  //json -> cart + name درون این قسمت
        'settled_at',
        'amount',
        'status',
    ];

    protected $casts = [
        'from' => 'json',
        'to' => 'json',
    ];

    //-------------------- RelationSHip
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    //-------------------- Methods
    public function getStatusCssColor()
    {
        if ($this->status == Settlement::STATUS_SETTLED) return 'text-success';
        elseif ($this->status == Settlement::STATUS_PENDING) return 'text-warning';
        else return 'text-danger_custom';
    }


}
