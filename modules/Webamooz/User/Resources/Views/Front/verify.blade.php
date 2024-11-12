@extends('User::Front.master')

@section('title' , 'ارسال کد ایمیل')


@section('content')
    <div class="account">

        <form action="{{ route('verification.verify') }}" class="form" method="post">
            @csrf
            <a class="account-logo" href="{{ route('home') }}">
                <img src="/img/weblogo.png" alt="">
            </a>
            <div class="card-header">
                <p class="activation-code-title">
                    کد فرستاده شده به ایمیل.  <span>{{ auth()->user()->email }}</span> را وارد کنید
                    ایمیلتان را اشتباه وارد کرده اید؟ <a href="{{ route('users.profile') }}">برای ویرایش ایمیل کلیک کنید</a>
                </p>

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

                <a href="#" onclick="
                event.preventDefault();
                document.getElementById('resend-code').submit()">ارسال مجدد کد فعال سازی</a>

                <div class="form-footer">
                    <a href="{{ route('register') }}">صفحه ثبت نام</a>
                </div>
            </div>
        </form>

        <form id="resend-code" action="{{ route('verification.resend') }}" method="post">
            @csrf
        </form>

    </div>
@endsection

@section('js')
    <script src="/js/jquery-3.4.1.min.js"></script>
    <script src="/js/activation-code.js"></script>
@endsection
