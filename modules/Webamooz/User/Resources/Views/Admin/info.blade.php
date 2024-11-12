@extends('Dashboard::master')

@section('title' , 'طلاعات کامل حساب کاربر')
@section('breadcrumb')
    <li><a href="#" title="کاربران">کاربران</a></li>
@endsection

@section('content')

    <div class="row no-gutters">

        <div class="col-12">
            <p class="box__title"> اطلاعات کامل حساب کاربری <strong>{{ $user->name }}</strong></p>
            <div class="margin-left-10 margin-bottom-20 border-radius-3 padding-20 bg-white w-100">
                <ul>
                    <li class="margin-bottom-5"> ایمیل : <strong><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></strong></li>
                    <li class="margin-bottom-5"> نام کاربری : <strong>{{ $user->username }}</strong></li>
                    <li class="margin-bottom-5"> موبایل : <strong>{{ $user->mobile }}</strong></li>
                    <li class="margin-bottom-5"> عنوان : <strong>{{ $user->head_line }}</strong></li>
                    <li class="margin-bottom-5"> بیو : <strong>{{ $user->bio }}</strong></li>
                    <li class="margin-bottom-5"> شماره کارت : <strong>{{ $user->card_number }}</strong></li>
                    <li class="margin-bottom-5"> شبا : <strong>{{ $user->sheba }}</strong></li>
                    <li class="margin-bottom-5"> موجودی حساب : <strong>{{ number_format($user->balance) }}</strong></li>
                    <li class="margin-bottom-5"> تلگرام : <strong>{{ $user->telegram }}</strong></li>
                    <li class="margin-bottom-5"> تاریخ تایید ایمیل :
                        <strong>{{ $user->email_verified_at ? \Morilog\Jalali\Jalalian::fromCarbon($user->email_verified_at) : 'تایید نشده' }}</strong></li>
                </ul>
            </div>
        </div>

        {{----- Purchases -----}}
        <div class="col-6 margin-left-10 margin-bottom-20 border-radius-3">
            <p class="box__title text-center">دوره های خریداری شده</p>
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                    <tr role="row" class="title-row">
                        <th>شناسه</th>
                        <th>دوره</th>
                        <th>مبلغ پرداخت شده</th>
                        <th>تاریخ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($user->purchases as $index => $purchase)
                        <tr role="row" class="">
                            <td>{{ $purchase->id }}</td>
                            <td><a href="{{ $purchase->path() }}" class="text-info_custom">{{ $purchase->title }}</a></td>
                            <td>{{ number_format($purchase->payment()->amount) }}</td>
                            <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($purchase->created_at) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{----- Courses -----}}
        <div class="col-6 margin-bottom-20 border-radius-3">
            <p class="box__title text-center">دوره های در حال تدریس</p>
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                    <tr role="row" class="title-row">
                        <th>شناسه</th>
                        <th>دوره</th>
                        <th>مبلغ</th>
                        <th>وضعیت</th>
                        <th>تاریخ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($user->courses as $index => $course)
                        <tr role="row" class="">
                            <td>{{ $course->id }}</td>
                            <td><a href="{{ $course->path() }}" class="text-info_custom">{{ $course->title }}</a></td>
                            <td>{{ $course->price == 0 ? 'رایگان' : number_format($course->price) }}</td>
                            <td>@lang($course->status)</td>
                            <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($course->created_at) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{----- Payments -----}}
        <div class="col-6 margin-left-10 margin-bottom-20 border-radius-3">
            <p class="box__title text-center">پرداخت ها</p>
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                    <tr role="row" class="title-row">
                        <th>شناسه</th>
                        <th>محصول</th>
                        <th>مبلغ پرداخت</th>
                        <th>وضعیت</th>
                        <th>تاریخ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($user->payments as $index => $payment)
                        <tr role="row" class="">
                            <td>{{ $payment->id }}</td>
                            <td><a href="{{ $payment->paymentable->path() }}" class="text-info_custom">{{ $payment->paymentable->title }}</a></td>
                            <td>{{ number_format($payment->amount) }}</td>
                            <td>@lang($payment->status)</td>
                            <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($payment->created_at) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        {{----- Settlements -----}}
        <div class="col-6 margin-bottom-20 border-radius-3">
            <p class="box__title text-center">درخواست های تسویه</p>
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                    <tr role="row" class="title-row">
                        <th>شناسه</th>
                        <th>مبلغ پرداخت</th>
                        <th>وضعیت</th>
                        <th>تاریخ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($user->settlements as $index => $settlement)
                        <tr role="row" class="">
                            <td>{{ $settlement->id }}</td>
                            <td>{{ number_format($settlement->amount) }}</td>
                            <td>@lang($settlement->status)</td>
                            <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($settlement->created_at) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>


    </div>
@endsection

@section('js')
    <script>
        @includeIf('Common::layouts.feedbacks')  {{-- show session flash --}}
    </script>
@endsection

