@extends('auth.master')

@section('title' , 'تایید ایمیل')


@section('content')
<div class="form">
    <a class="account-logo" href="/">
        <img src="/img/weblogo.png" alt="">
    </a>

    <div class="form-content form-account">
        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                {{ __('یک پیوند تأیید جدید به آدرس ایمیل شما ارسال شده است.') }}
            </div>
        @endif

        {{ __('قبل از ادامه، لطفاً ایمیل خود را برای پیوند تأیید بررسی کنید') }}،
        {{ __('اگر ایمیل را دریافت نکردید درخواست ارسال مجدد لینک نمایید') }}
        <form class="d-inline center" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <br/>
            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('ارسال مجدد لینک تایید') }}</button>
            <br/>
            <br/>
            <a href="/">{{ __('بازگشت به صفحه اصلی') }}</a>
        </form>
    </div>

</div>
@endsection
