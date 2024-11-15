@extends('Dashboard::master')
@section('title' , 'پنل ادمین')

@section('content')
    @can(\Webamooz\RolePermissions\Model\Permission::PERMISSION_TEACH)
        <div class="row no-gutters font-size-13 margin-bottom-10">
            <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
                <p> موجودی حساب فعلی </p>
                <p>{{ number_format(auth()->user()->balance) }} تومان </p>
            </div>
            <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
                <p> کل فروش دوره ها</p>
                <p>{{ number_format($totalSells) }} تومان</p>
            </div>
            <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
                <p> کارمزد کسر شده </p>
                <p>{{ number_format($totalSiteShare) }} تومان</p>
            </div>
            <div class="col-3 padding-20 border-radius-3 bg-white margin-bottom-10">
                <p> درآمد خالص </p>
                <p>{{ number_format($totalBenefit) }} تومان</p>
            </div>
        </div>
        <div class="row no-gutters font-size-13 margin-bottom-10">

            <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
                <p> درآمد امروز </p>
                <p>{{ number_format($todayBenefit) }} تومان</p>
            </div>

            <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
                <p> درآمد 30 روز گذاشته</p>
                <p>{{ number_format($last30DaysBenefit) }} تومان</p>
            </div>

            <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
                <p> تسویه حساب در حال انجام </p>
                <p>0 تومان </p>
            </div>

            <div class="col-3 padding-20 border-radius-3 bg-white  margin-bottom-10">
                <p> تراکنش های موفق امروز ({{ $todaySuccessPaymentCount }}) تراکنش </p>
                <p>{{ number_format($todaySuccessPaymentTotal) }} تومان</p>
            </div>

        </div>
        <div class="row no-gutters font-size-13 margin-bottom-10">

            {{---------- Highcharts.chart ----------}}
            <div class="col-8 padding-20 bg-white margin-bottom-10 margin-left-10 border-radius-3">
                <div class="col-12 bg-white padding-30 margin-bottom-20">
                    <div id="container"></div>
                </div>
            </div>


            <div class="col-4 info-amount padding-20 bg-white margin-bottom-12-p margin-bottom-10 border-radius-3">

                <p class="title icon-outline-receipt">موجودی قابل تسویه </p>
                <p class="amount-show color-444">600,000<span> تومان </span></p>
                <p class="title icon-sync">در حال تسویه</p>
                <p class="amount-show color-444">0<span> تومان </span></p>
                <a href="/" class=" all-reconcile-text color-2b4a83">همه تسویه حساب ها</a>
            </div>
        </div>
    @endcan ()

    <div class="row bg-white no-gutters font-size-13">
        <div class="title__row">
            <p>تراکنش های اخیر شما</p>
            <a class="all-reconcile-text margin-left-20 color-2b4a83">نمایش همه تراکنش ها</a>
        </div>
        <div class="table__box">
            <table width="100%" class="table">
                <thead role="rowgroup">
                <tr role="row" class="title-row">
                    <th>شماره</th>
                    <th>شناسه پرداخت</th>
                    <th>نام و نام خانوادگی</th>
                    <th>ایمیل پرداخت کننده</th>
                    <th>مبلغ (تومان)</th>
                    <th>درامد شما</th>
                    <th>درامد سایت</th>
                    <th>نام دوره</th>
                    <th>تاریخ و ساعت</th>
                    <th>وضعیت</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($payments as $index => $payment)
                    <tr role="row" class="">
                        <td>{{ $payments->firstItem() + $index  }}</td>
                        <td>{{ $payment->invoice_id }}</td>
                        <td>{{ $payment->buyer->name }}</td>
                        <td>{{ $payment->buyer->email }}</td>
                        <td>{{ number_format($payment->amount) }}</td>
                        <td>{{ number_format($payment->seller_share) }}</td>
                        <td>{{ number_format($payment->site_share) }}</td>
                        <td>{{ $payment->paymentable->title }}</td>
                        <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($payment->created_at) }}</td> {{-- fromCarbon ==fromDateTime -> Miladi To Jalili --}}
                        <td class="
                                @if ($payment->status == \Webamooz\Payment\Models\Payment::STATUS_SUCCESS) text-success
                                @elseif ($payment->status == \Webamooz\Payment\Models\Payment::STATUS_FAIL) text-danger_custom
                                @elseif ($payment->status == \Webamooz\Payment\Models\Payment::STATUS_PENDING) text-warning
                                @elseif ($payment->status == \Webamooz\Payment\Models\Payment::STATUS_CANCELED) text-white-50_custom
                                @endif">
                            @lang($payment->status)
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $payments->render() }}
        </div>
    </div>

@endsection

@section('js')
    @includeIf('Payment::chart')
@endsection
