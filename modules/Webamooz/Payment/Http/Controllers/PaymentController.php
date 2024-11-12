<?php

namespace Webamooz\Payment\Http\Controllers;

use App\Helper\Generate;
use App\Http\Controllers\Controller;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use Webamooz\Payment\Events\PaymentWasSuccessful;
use Webamooz\Payment\Gateways\Gateway;
use Webamooz\Payment\Models\Payment;
use Webamooz\Payment\Repositories\PaymentRepositories;

class PaymentController extends Controller
{

    private $paymentRepository;

    public function __construct(PaymentRepositories $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function index(Request $request)
    {
        $this->authorize('manage', Payment::class);
        $payments = $this->paymentRepository
            ->searchEmail($request->email)
            ->searchAmount($request->amount)
            ->searchInvoiceId($request->invoice_id)
            ->searchAfterDate(Generate::getDateJaliliToMiadi($request->start_date))
            ->searchBeforeDate(Generate::getDateJaliliToMiadi($request->end_date))
            ->paginate(12);

        //---------- Total+Benefit
        $last3DaysTotal = $this->paymentRepository->getLastNDaysTotal(30);  //کل فروش ۳۰ روز گذشته سایت
        $last3DaysBenefit = $this->paymentRepository->getLastNDaysSiteBenefit(30);  //درامد خالص ۳۰ روز گذشته سایت
        $last3DaysSellerShare = $this->paymentRepository->getLastNDaysSellerShare(30);  //درامد 30 روز مدرس
        $totalAllSite = $this->paymentRepository->getLastNDaysTotal();  //کل فروش سایت
        $benefitAllSite = $this->paymentRepository->getLastNDaysSiteBenefit();  //کل درآمد خالص سایت

        $dates = collect();
        foreach (range(-30, 0) as $i) {  //range($period, 0) -> میاد میگه از مقدار آخری که گفته مثلا 30 روز بیا تا عدد صفر بهش بده
            $dates->put(now()->addDays($i)->format('Y-m-d'), 0);  //->put -> برای ریختن مقدار درون کانشن که صورت کلید و مقدار میتونیم دسترسی داشته باشیم با کلید
        }
        $summery = $this->paymentRepository->getRangeDailySummery($dates);

        return view('Payment::index', compact('payments', 'last3DaysTotal', 'last3DaysBenefit', 'last3DaysSellerShare',
            'totalAllSite', 'benefitAllSite', 'dates', 'summery'));
    }

    public function purchases()
    {
        $payments = auth()->user()->payments()->with('paymentable')->paginate(12);  //with('paymentable') -> Query easy
        return view('Payment::purchases' , compact('payments'));
    }

    public function callback(Request $request)
    {
        $gateway = resolve(Gateway::class);
        $paymentRepo = new PaymentRepositories();
        $payment = $paymentRepo->findByInvoiceId($gateway->getInvoiceIdFromRequest($request));
        if (!$payment) {
            $this->newFeedback("تراکنش ناموفق", "تراکنش مورد نظر یافت نشد", "error");
            return to_route('home');
        }

        $result = $gateway->verify($payment);
        if (is_array($result)) {  //ZarinpalAdaptor.php -> method verify -> وقتی آرایه بر میگردونه یعنی خطا داره
            $this->newFeedback("عملیات ناموفق", $result['message'], "error");
            $paymentRepo->changeStatus($payment->id, Payment::STATUS_FAIL);

        } else {
            event(new PaymentWasSuccessful($payment));  //این رویداد صدا میزنیم درون کل پرويه + $payment مشخص کنیم که کدوم دوره یا درگاه کی پرداخت کرده
            $this->newFeedback("عملیات موفقیت آمیز", "پرداخت با موفقیت انجام شد", "success");
            $paymentRepo->changeStatus($payment->id, Payment::STATUS_SUCCESS);
        }
        return redirect($payment->paymentable->path());  //چه عملیات خطا چه درست باش میره درون این صفحه
    }


    function newFeedback($heading = 'موفقیت آمیر', $text = 'عملیات با موفقیت انجام شد', $type = 'success')
    {
        $session = session()->has('feedbacks') ? session()->get('feedbacks') : [];  //هر چند تا که بسازیم سشن به اسم فیدبکز میاد صدا میزنه فراخوانی میکنه
        $session[] = ["heading" => $heading, "text" => $text, "type" => $type];  //session is array
        session()->flash('feedbacks', $session);
    }

}
