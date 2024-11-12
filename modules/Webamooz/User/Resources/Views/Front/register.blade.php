@extends('User::Front.master')
@section('title' , 'صفحه ثبت نام')
{{--@section('title')صفحه ثبت@endsection--}}


@section('content')
    <form action="{{ route('register') }}" class="form" method="post">
        @csrf

        <a class="account-logo" href="/">
            <img src="/img/weblogo.png" alt="">
        </a>
        <div class="form-content form-account">
            {{--
            autocomplete="name" -> نمایش اطلاعات ثبت شده ما و کمک به نمایش آن ها + مواردی که سرچ کردیم قبلا مثل سرچ های گوگل  +
            autocomplete="new-password" -> خود مرورگر خودکار یک پسوورد پیشنهاد بدهد
            autofocus -> input,select,textarea,button اشاره گر متن نوشتن به صورت خودکار درون اینپوت و تکست آریاو سلکت و دکمه فقط برای
            {{ old('name') }} -> اگر متنی نوشتیم به خطایی برخوردیم برامون نگه داره متن قدیمی
            class="txt @error('name') is-invalid @enderror" -> is-invalid اگر خطایی برای اسم اینپوت وجود داشت اضافه بکن کلاس
            --}}

            <input type="text" class="txt @error('name') is-invalid @enderror" placeholder="نام و نام خانوادگی *"
                   value="{{ old('name') }}" name="name" required autocomplete="name" autofocus>
            @error('name')
            <span class="invalid-feedback" role="alert">
                <p style="color: crimson">{{ $message }}</p>
            </span>
            @enderror

            <input id="email" type="email" class="txt txt-l @error('email') is-invalid @enderror" placeholder="ایمیل *"
                   value="{{ old('email') }}" name="email" required autocomplete="email">
            @error('email')
            <span class="invalid-feedback" role="alert">
                <p style="color: crimson">{{ $message }}</p>
            </span>
            @enderror

            <input id="mobile" type="text" class="txt txt-l @error('mobile') is-invalid @enderror" placeholder="شماره موبایل"
                   value="{{ old('mobile') }}" name="mobile" autocomplete="mobile" />
            @error('mobile')
            <span class="invalid-feedback" role="alert">
                <p style="color: crimson">{{ $message }}</p>
            </span>
            @enderror

            <input id="password" type="password" class="txt txt-l @error('password') is-invalid @enderror" placeholder="رمز عبور *"
                   value="{{ old('password') }}" name="password" required autocomplete="new-password">
            @error('password')
            <span class="invalid-feedback" role="alert">
                <p style="color: crimson">{{ $message }}</p>
            </span>
            @enderror

            <input id="password-confirm" type="password" class="txt txt-l @error('password') is-invalid @enderror"
                   placeholder="تایید رمز عبور *" value="{{ old('password') }}"
                   name="password_confirmation" required autocomplete="new-password">

            <span class="rules">رمز عبور باید حداقل ۶ کاراکتر و ترکیبی از حروف بزرگ،
                حروف کوچک، اعدادو کاراکترهای غیر الفبا مانند #?!@$ %^&*- باشد.</span>
            <br>
            <button class="btn continue-btn" autofocus>ثبت نام و ادامه</button>

        </div>
        <div class="form-footer">
            <a href="{{ route('login') }}">صفحه ورود</a>
        </div>
    </form>
@endsection
