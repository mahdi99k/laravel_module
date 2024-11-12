<?php

namespace Webamooz\Payment\Repositories;

use Illuminate\Support\Facades\DB;
use Webamooz\Payment\Models\Payment;


class PaymentRepositories
{
    //---------- Search payment
    private $query;

    public function __construct()
    {
        $this->query = Payment::query();
    }

    public function paginate($count)
    {
        return $this->query->latest()->paginate($count);
    }

    public function searchEmail($email)
    {
        if (!is_null($email)) {
            //1)join(با چه جدولی ارتباط بگیره)  2)table.column(table Other)   3)tableJoin(ارتباط گیرنده جدول جوین شده)
            $this->query->join('users', 'payments.buyer_id', 'users.id')
                ->select("payments.*", "users.email")
                ->where('users.email', "LIKE", "%" . $email . "%");
        }
        return $this;  //اگر سرچی نبود و ایمیل نفرستاده بودیم بیا صفحه اصلی نمایش بده همراه صفحه بندی
    }

    public function searchAmount($amount)
    {
        if (!is_null($amount)) {
            $this->query->where('amount', $amount);
        }
        return $this;  //اگر سرچی نبود و مبلغ نفرستاده بودیم بیا صفحه اصلی نمایش بده همراه صفحه بندی
    }


    public function searchInvoiceId($invoice_id)
    {
        if (!is_null($invoice_id)) {
            $this->query->where('invoice_id', "LIKE", "%" . $invoice_id . "%");
        }
        return $this;  //اگر سرچی نبود و تراکنش نفرستاده بودیم بیا صفحه اصلی نمایش بده همراه صفحه بندی
    }

    public function searchAfterDate($date)
    {
        if (!is_null($date)) {
            $this->query->whereDate('created_at', '>=', $date);  //searchBeforeDate -> مقایسه بین دو تا تاریخ زمانی
        }
        return $this;  //اگر سرچی نبود و تاریخ شروع نفرستاده بودیم بیا صفحه اصلی نمایش بده همراه صفحه بندی
    }

    public function searchBeforeDate($date)
    {
        if (!is_null($date)) {
            $this->query->whereDate('created_at', '<=', $date);  //searchBeforeDate -> مقایسه بین دو تا تاریخ زمانی
        }
        return $this;  //اگر سرچی نبود و تاریخ پایان نفرستاده بودیم بیا صفحه اصلی نمایش بده همراه صفحه بندی
    }

    //---------- Search payment


    public function findByInvoiceId($invoice_id)
    {
        return Payment::where('invoice_id', $invoice_id)->first();
    }


    //---------- Total Site , Teacher -> Payment
    public function getLastNDaysPayments($status, $days = null)
    {
        $query = Payment::query();

        if (!is_null($days)) $query->where('created_at', '>=', now()->addDays(-$days));

        return $query->where('status', $status)->latest();  //ممکن در آمد یا فروش 30 روز گذشته یا کلی سایت بخواد
    }

    public function getLastNDaysSuccessPayment($days = null): \Illuminate\Database\Eloquent\Builder
    {
        return $this->getLastNDaysPayments(Payment::STATUS_SUCCESS, $days);
    }

    public function getLastNDaysTotal($days = null)  //کل فروش ۳۰ روز گذشته سایت
    {
        return $this->getLastNDaysSuccessPayment($days)->sum('amount');  //در آمد سایت و مدرس با هم فروش کلی سایت
    }

    public function getLastNDaysSiteBenefit($days = null)  //درامد خالص ۳۰ روز گذشته سایت
    {
        return $this->getLastNDaysSuccessPayment($days)->sum('site_share');  //در آمد خالص فقط سایت بدون مدرس
    }

    public function getLastNDaysSellerShare($days = null)
    {
        return $this->getLastNDaysSuccessPayment($days)->sum('seller_share');
    }
    //---------- Total Site , Teacher -> Payment


    //---------- Total Site , Teacher -> Home Dashboard
    public function paymentBySellerId($user_Id)
    {
        return Payment::query()->where('seller_id', $user_Id);
    }

    public function getUserSuccessPayments($user_id)
    {
        return Payment::where('seller_id', $user_id)->where('status', Payment::STATUS_SUCCESS);
    }

    public function getUserTotalSells($user_id)
    {
        return $this->getUserSuccessPayments($user_id)->sum('amount');  //فقط قیمت های مدرس
    }

    public function getUserTotalBenefit($user_id)
    {
        return $this->getUserSuccessPayments($user_id)->sum('seller_share');  //فقط قیمت های مدرس
    }

    public function getUserTotalSiteShare($user_id)
    {
        return $this->getUserSuccessPayments($user_id)->sum('site_share');  //فقط قیمت سایت
    }

    public function getUserTotalBenefitByDay($user_id, $date)
    {
        return $this->getUserSuccessPayments($user_id)->whereDate('created_at', $date)->sum('seller_share');
    }

    public function getUserTotalSellByDay($user_id, $date)
    {
        return $this->getUserSuccessPayments($user_id)->whereDate('created_at', $date)->sum('amount');
    }

    public function getUserSellByDayCount($user_id, $date)
    {
        return $this->getUserSuccessPayments($user_id)->whereDate('created_at', $date)->count();
    }

    public function getUserTotalBenefitByPeriod($user_id, $startDate, $endDate)
    {
        return $this->getUserSuccessPayments($user_id)  //امروز پنجم برج بیا از تاریخ امروز کوچیکتر بگیره و تا پنجم برج قبل باید از اون بزرگتر باشه
        ->whereDate('created_at', '<=', $startDate)
            ->whereDate('created_at', '>=', $endDate)
            ->sum('seller_share');
    }
    //---------- Total Site , Teacher -> Home Dashboard


    //---------- Highcharts.chart
    public function getDayPayments($day, $status)  //بر اسا ی روز خاص میری تراکنش های موفق اون روز حساب میکنی
    {
        //whereDate -> جایی که این تاریخ برابر تاریخ که بهش میدیم  +  مقایسه دو تا تاریخ
        return Payment::query()->whereDate("created_at", $day)->where('status', $status)->latest();
    }

    public function getDaySuccessPayments($day)
    {
        return $this->getDayPayments($day, Payment::STATUS_SUCCESS);
    }

    public function getDaySuccessPaymentTotal($day)
    {
        return $this->getDaySuccessPayments($day)->sum('amount');  //فروش کلی سایت و مدرس 30 روز
    }

    public function getDaySiteShare($day)
    {
        return $this->getDaySuccessPayments($day)->sum('site_share');  //در آمد خالص 30 روز سایت
    }

    public function getDaySellerShare($day)
    {
        return $this->getDaySuccessPayments($day)->sum('seller_share');  //در آمد مدرس سایت
    }

    public function getDayFailedPayments($day)
    {
        return $this->getDayPayments($day, Payment::STATUS_FAIL);
    }

    public function getDayFailedPaymentTotal($day)
    {
        return $this->getDayFailedPayments($day)->sum('amount');
    }
    //---------- Highcharts.chart


    //---------- Highcharts.chart Refactoring
    public function getRangeDailySummery($dates, $seller_id = null)
    {
        //dates->keys()->first -> بیا فقط کلید ها بگیر یعنی تاریخ ها و اولی نمایش بده که همون قدیمی ترین تاریخ
        $query = Payment::where('created_at', '>=', $dates->keys()->first())  //summery -> روز هایی نمایش میده که فروش داشتیم درونش
        ->groupBy("date")
            ->orderBy("date");

        if (!is_null($seller_id)) {  //برای مشخص شدن چارت مدرس با چارت کلی سایت برای ادمین
            $query->where('seller_id', $seller_id)->where('status', Payment::STATUS_SUCCESS);
        }

        return $query->get([
            DB::raw("DATE(created_at) as date"),  //column(database) as newField -> میاد ی قسمت از دیتابیس ما میریزه درون ی مقدار برای ساختن فیلد
            DB::raw("SUM(amount) as totalAmount"),
            DB::raw("SUM(site_share) as totalSiteShare"),
            DB::raw("SUM(seller_share) as totalSellerShare"),
        ]);
    }

    //---------- Highcharts.chart Refactoring


    public function store($data, $discounts = [])
    {
        $payment = Payment::create([
            'buyer_id' => $data['buyer_id'],
            'seller_id' => $data['seller_id'],
            'paymentable_id' => $data['paymentable_id'],
            'paymentable_type' => $data['paymentable_type'],
            'amount' => $data['amount'],
            'invoice_id' => $data['invoice_id'],
            'gateway' => $data['gateway'],
            'status' => $data['status'],
            'seller_percent' => $data['seller_percent'],
            'seller_share' => $data['seller_share'],
            'site_share' => $data['site_share'],
        ]);
        foreach ($discounts as $discount) {
            $discountIds[] = $discount->id;  //میاد آیدی های تخفیف مثل سراسری یا کد تخفیفی میریزه درون آرایه نهایتا دو تا تخفیف
        }
        if (isset($discountIds)) {
            $payment->discounts()->sync($discountIds);
        }
        return $payment;
    }

    public function changeStatus($payment_id, $status)
    {
        return Payment::where('id', $payment_id)->update([
            'status' => $status
        ]);
    }

}
