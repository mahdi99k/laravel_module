<?php

namespace Webamooz\Payment\Service;

use App\Helper\Generate;
use Webamooz\Payment\Models\Settlement;
use Webamooz\Payment\Repositories\SettlementRepositories;

class SettlementService
{

    public static function store($data)  //$data === $request
    {
        $repo = new SettlementRepositories();
        $repo->store($data);
        auth()->user()->balance -= $data->amount;
        auth()->user()->save();
        Generate::newFeedback();
    }

    public static function update(int $settlement_id, $data)  //$data === $request
    {
        $repo = new SettlementRepositories();
        $settlement = $repo->findById($settlement_id);

        //وضعیت درون دیتابیس نباید رد شده یا کنسل باشد که دوباره بیاد پول بریزه به حساب میتونه در حال انتظار باشد که بزنیم رد یا لغو پول ی بار برگشت بزنه
        if (!in_array($settlement->status, [Settlement::STATUS_CANCELED, Settlement::STATUS_REJECTED]) &&
            in_array($data->status, [Settlement::STATUS_CANCELED, Settlement::STATUS_REJECTED])) {  //needle(سوزن)  haystack(انبار کاه)
            $settlement->user->balance += $settlement->amount;  //اگر کنسل یا رد شد بیا اون مقدار پولی که مدرس درخواست داده بود برگردون به حساب کیف پولش
            $settlement->user->save();
            to_route('settlements.index');
        }

        //اگر در حالت درخواست تسویه مقدار پول درخواستی کم میکنه و در حالت در حال انتظار که یا بکنیم تسویه شده همون عدد میمونه پولی کم و زیاد نمیشه
        // اگر لغو یا رد باش بکنیم تسویه یا در حال انتظار میاد از حساب مدرس کم میکنه
        if (in_array($settlement->status, [Settlement::STATUS_CANCELED, Settlement::STATUS_REJECTED]) &&
            in_array($data->status, [Settlement::STATUS_SETTLED, Settlement::STATUS_PENDING])) {

            //وقتی وضعیت لغو یا رد و موجودی کاربر صفر و میخوایم تایید یا در حال انتظار بکنیم این میاد مقدار مبلغ تسویه کم میکنه که منفی میشیم
            if ($settlement->user->balance < $settlement->amount) {
                Generate::newFeedback("ناموفق", "موجودی حساب کاربر کافی نمی باشد", "error");
                return to_route('settlements.index');
            }

            $settlement->user->balance -= $settlement->amount;
            $settlement->user->save();
            to_route('settlements.index');
        }



        $repo->update($settlement_id, $data);
        Generate::newFeedback();
    }

}
