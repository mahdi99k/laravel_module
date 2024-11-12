@extends('User::Front.master')

@section('title' , 'ارسال کد ایمیل')


@section('content')
    <form action="{{ route('verification.verify') }}" class="form" method="post">
        @csrf
        <a class="account-logo" href="/">
            <img src="/img/weblogo.png" alt="">
        </a>
        <div class="card-header">
            <p class="activation-code-title">کد فرستاده شده به ایمیل  <span>{{ auth()->user()->email }}</span> را وارد کنید</p>
        </div>
        <div class="form-content form-content1">
            <button class="btn i-t">تایید کردن ایمیل</button>

        </div>
        <div class="form-footer">
            <a href="login">صفحه ثبت نام</a>
        </div>
    </form>
@endsection







