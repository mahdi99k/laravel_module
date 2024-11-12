<?php

namespace Webamooz\Payment\Repositories;

use Webamooz\Payment\Models\Settlement;

class SettlementRepositories
{

    private $query;

    public function __construct()
    {
        $this->query = Settlement::query();
    }

    public function latest()
    {
        $this->query = $this->query->latest();
        return $this->query;
    }

    public function findById($settlement_id)
    {
        return $this->query->findOrFail($settlement_id);
    }


    public function paginate($count = 10)
    {
        return $this->query->latest()->paginate($count);
    }

    public function paginateUserSettlements($count = 10, int $user_id)
    {
        return $this->query->where('user_id', $user_id)->latest()->paginate($count);
    }

    public function settled()
    {
        return $this->query->where('status', Settlement::STATUS_SETTLED);
    }

    public function getLastPendingSettlement($user_id)  //آخرین مقدار وضعیت در حال انتظار کاربر پیدا کن اگر وجود داشت نتونه درخواست جدیدی ارسال کنه
    {
        return Settlement::query()->where('user_id', $user_id)->where('status', Settlement::STATUS_PENDING)->latest()->first();
    }

    public function getLastSettlement($user_id)  //بتونه آخرین درخواست خودش ویرایش کنه ما بقی نتونه
    {
        return Settlement::query()->where('user_id', $user_id)->latest()->first();
    }

    public function store($request)
    {
        return Settlement::create([
            'user_id' => auth()->id(),
            'to' => [  //json
                "name" => $request['name'],
                "cart" => $request['cart'],
            ],
            "amount" => $request->amount
        ]);
    }

    public function update(int $settlement_id, $request)
    {
        return Settlement::query()->where('id', $settlement_id)->update(
            [
                "from" => [
                    "name" => $request->from['name'],
                    "cart" => $request->from['cart'],
                ],
                "to" => [
                    "name" => $request->to['name'],
                    "cart" => $request->to['cart']
                ],
                "status" => $request->status
            ]
        );
    }

}
