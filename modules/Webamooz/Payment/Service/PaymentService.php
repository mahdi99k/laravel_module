<?php

namespace Webamooz\Payment\Service;

use Webamooz\Payment\Gateways\Gateway;
use Webamooz\Payment\Gateways\Zarinpal\ZarinpalAdaptor;
use Webamooz\Payment\Models\Payment;
use Webamooz\Payment\Repositories\PaymentRepositories;
use Webamooz\User\Models\User;

class PaymentService
{

    public static function generate($amount, $paymentable, User $buyer, $seller_id = null, $discounts = [])  //$paymentable -> $course
    {
        if ($amount <= 0 || is_null($paymentable->id) || is_null($buyer->id)) return false;
        $gateway = resolve(Gateway::class);
        $invoiceId = $gateway->request($amount, $paymentable->title);
        if (is_array($invoiceId)) {
            //todo
            dd($invoiceId);
        }

        if (!is_null($paymentable->percent)) {
            $seller_percent = $paymentable->percent;
            $seller_share = ($amount / 100) * $seller_percent;
            $site_share = $amount - $seller_share;  //($amount / 100) * (100 - $seller_percent)

        } else {
            $seller_percent = $seller_share = 0;
            $site_share = $amount;  //اگر دوره ای رایگان کل قیمت برای سایت
        }

        return resolve(PaymentRepositories::class)->store([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller_id,
            'paymentable_id' => $paymentable->id,
            'paymentable_type' => get_class($paymentable),
            'amount' => $amount,
            'invoice_id' => $invoiceId,
            'gateway' => $gateway->getName(),
            'status' => Payment::STATUS_PENDING,
            'seller_percent' => $seller_percent,
            'seller_share' => $seller_share,
            'site_share' => $site_share,
        ], $discounts);
    }

}
