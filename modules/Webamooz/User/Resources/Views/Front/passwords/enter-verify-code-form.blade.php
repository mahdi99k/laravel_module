@extends('User::Front.master')

@section('title' , 'ارسال کد بازیابی رمز عبور')


@section('content')
    <div class="account">

        <form action="{{ route('password.checkVerifyCode') }}" class="form" method="post">
            @csrf
            <a class="account-logo" href="{{ route('home') }}">
                <img src="/img/weblogo.png" alt="">
            </a>
            <input type="hidden" name="email" value="{{ request()->email }}">
            <div class="card-header">
                <p class="activation-code-title">کد فرستاده شده به ایمیل  <span>{{ request()->email }}</span> را وارد کنید</p>
            </div>
            <div class="form-content form-content1">
                <input name="verify_code" required class="activation-code-input" placeholder="فعال سازی">
                @error('verify_code')
                <span class="invalid-feedback" role="alert">
                    <strong style="color: crimson">{{ $message }}</strong>
                </span>
                @enderror
                <br>
                <button class="btn i-t">تایید</button>

                <a href="{{ route('password.sendVerifyCodeEmail') }}?email={{ request()->email }}">ارسال مجدد کد فعال سازی</a>

                <div class="form-footer">
                    <a href="{{ route('register') }}">صفحه ثبت نام</a>
                </div>
            </div>
        </form>

        <!--<form id="resend-code" action="{{ route('verification.resend') }}" method="post">
            @csrf
        </form>-->

    </div>
@endsection

@section('js')
    <script src="/js/jquery-3.4.1.min.js"></script>
    <script src="/js/activation-code.js"></script>
@endsection
