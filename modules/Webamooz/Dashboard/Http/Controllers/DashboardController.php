<?php

namespace Webamooz\Dashboard\Http\Controllers;

use App\Helper\Generate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Webamooz\Payment\Repositories\PaymentRepositories;
use Webamooz\RolePermissions\Model\Permission;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function home(PaymentRepositories $paymentRepositories)
    {
        $user_id = auth()->user()->id;
        //---------- Dashboard Home
        $totalSells = $paymentRepositories->getUserTotalSells($user_id);
        $totalBenefit = $paymentRepositories->getUserTotalBenefit($user_id);
        $totalSiteShare = $paymentRepositories->getUserTotalSiteShare($user_id);
        $todayBenefit = $paymentRepositories->getUserTotalBenefitByDay($user_id, now());  //درآمد امروز
        $last30DaysBenefit = $paymentRepositories->getUserTotalBenefitByPeriod($user_id, now(), now()->addDays(-30));  //درآمد امروز
        $todaySuccessPaymentTotal = $paymentRepositories->getUserTotalSellByDay($user_id, now());
        $todaySuccessPaymentCount = $paymentRepositories->getUserSellByDayCount($user_id, now());
        //---------- Chart.js
        $payments = $paymentRepositories->paymentBySellerId($user_id)->paginate(12);
        $last3DaysTotal = $paymentRepositories->getLastNDaysTotal(30);  //کل فروش ۳۰ روز گذشته سایت
        $last3DaysSellerShare = $paymentRepositories->getLastNDaysSellerShare(30);  //درامد 30 روز مدرس
        $totalAllSite = $paymentRepositories->getLastNDaysTotal();  //کل فروش سایت
        $last3DaysBenefit = $paymentRepositories->getUserTotalSiteShare($user_id);


        $dates = collect();
        foreach (range(-30, 0) as $i) {  //range($period, 0) -> میاد میگه از مقدار آخری که گفته مثلا 30 روز بیا تا عدد صفر بهش بده
            $dates->put(now()->addDays($i)->format('Y-m-d'), 0);  //->put -> برای ریختن مقدار درون کانشن که صورت کلید و مقدار میتونیم دسترسی داشته باشیم با کلید
        }
        $summery = $paymentRepositories->getRangeDailySummery($dates, $user_id);

        return view('Dashboard::index', compact('totalSells', 'totalBenefit', 'totalSiteShare', 'todayBenefit',
            'last30DaysBenefit', 'todaySuccessPaymentTotal', 'todaySuccessPaymentCount', 'last3DaysTotal', 'last3DaysSellerShare',
            'totalAllSite', 'payments', 'dates', 'summery', 'last3DaysBenefit'));
    }
}
