@extends('Dashboard::master')

@section('title' , 'لیست تراکنش ها')
@section('breadcrumb')
    <li><a href="#" title="تراکنش ها">تراکنش ها</a></li>
@endsection

@section('content')

    <div class="row no-gutters">

        {{---------- Total + Benefit ----------}}
        <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
            <p>کل فروش ۳۰ روز گذشته سایت </p>
            <p>{{ number_format($last3DaysTotal) }} تومان</p>
        </div>
        <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
            <p>درامد خالص ۳۰ روز گذشته سایت</p>
            <p>{{ number_format($last3DaysBenefit) }} تومان</p>
        </div>
        <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
            <p>کل فروش سایت</p>
            <p>{{ number_format($totalAllSite) }} تومان</p>
        </div>
        <div class="col-3 padding-20 border-radius-3 bg-white margin-bottom-10">
            <p> کل درآمد خالص سایت</p>
            <p>{{ number_format($benefitAllSite) }} تومان</p>
        </div>
    </div>



    {{---------- Highcharts.chart ----------}}
    <div class="row no-gutters border-radius-3 font-size-13">
        <div class="col-12 bg-white padding-30 margin-bottom-20">
            <div id="container"></div>
        </div>
    </div>


    <div class="d-flex flex-space-between item-center flex-wrap padding-30 border-radius-3 bg-white">
        <p class="margin-bottom-15">همه تراکنش ها</p>
        <div class="t-header-search">

            <form action="">
                <div class="t-header-searchbox font-size-13">
                    <input type="text" class="text search-input__box font-size-13" placeholder="جستجوی تراکنش">
                    <div class="t-header-search-content ">
                        <input type="text" name="email" class="text" placeholder="ایمیل" value="{{ request()->email }}">
                        <input type="text" name="amount" class="text" placeholder="مبلغ به تومان"
                               value="{{ request()->amount }}">
                        <input type="text" name="invoice_id" class="text" placeholder="شماره تراکنش"
                               value="{{ request()->invoice_id }}">
                        <input type="text" name="start_date" class="text" placeholder="از تاریخ : 1403/05/10"
                               value="{{ request()->start_date }}">
                        <input type="text" name="end_date" class="text" placeholder="تا تاریخ : 1403/05/11"
                               value="{{ request()->end_date }}">
                        <button type="submit" class="btn btn-webamooz_net mt-5">جستجو</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="col-12 margin-left-10 margin-bottom-15 border-radius-3">
        <p class="box__title">تراکنش ها</p>
        <div class="table__box">
            <table class="table">
                <thead role="rowgroup">
                <tr role="row" class="title-row">
                    <th>شناسه</th>
                    <th>تراکنش</th>
                    <th>نام و نام خانوادگی</th>
                    <th>ایمیل پرداخت کننده</th>
                    <th>میلغ(تومان)</th>
                    <th>درآمد مدرس</th>
                    <th>درآمد سایت</th>
                    <th>نام دوره</th>
                    <th>تاریخ و ساعت</th>
                    <th>وضعیت</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($payments as $index => $payment)
                    <tr role="row" class="">
                        <td>{{ $payment->id }}</td>
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
            {{ $payments->links() }}
        </div>
    </div>
@endsection


@section('js')
    <script>
        @includeIf('Common::layouts.feedbacks')  {{-- show session flash --}}
    </script>
    @includeIf('Payment::chart')
@endsection

