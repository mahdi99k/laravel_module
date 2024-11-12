@extends('Dashboard::master')

@section('title' , 'خرید های من')
@section('breadcrumb')
    <li><a href="#" title="خرید های من">خرید های من</a></li>
@endsection

@section('content')
    <div class="table__box">
        <table class="table">
            <thead role="rowgroup">
            <tr role="row" class="title-row">
                <th>عنوان دوره</th>
                <th>تاریخ پرداخت</th>
                <th>مقدار پرداختی</th>
                <th>وضعیت پرداخت</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($payments as $payment)
                <tr role="row" class="">
                    <td><a target="_blank" href="{{ $payment->paymentable->path() }}" class="text-info">{{ $payment->paymentable->title }}</a></td>
                    <td>{{ \App\Helper\Generate::createFromCarbon($payment->created_at) }}</td>
                    <td>{{ number_format($payment->amount) }} تومان </td>
                    <td class="
                        @if ($payment->status == \Webamooz\Payment\Models\Payment::STATUS_SUCCESS) text-success
                        @elseif ($payment->status == \Webamooz\Payment\Models\Payment::STATUS_PENDING) text-warning
                        @else text-danger_custom
                        @endif">
                        @lang($payment->status)
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $payments->render() }}  {{-- $payments->links() === $payments->render() --}}
    </div>
@endsection
