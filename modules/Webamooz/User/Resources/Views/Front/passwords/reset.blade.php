@extends('User::Front.master')
@section('title' , 'بازیابی رمز عبور')



@section('content')
    <form method="POST" action="{{ route('password.update') }}" class="form">
        <a class="account-logo" href="/">
            <img src="/img/weblogo.png" alt="">
        </a>
        @csrf
        {{--<input type="hidden" name="token" value="{{ $token }}">--}}
        <span> نام : {{ auth()->user()->name }}</span>

        <input id="password" type="password" class="txt txt-l @error('password') is-invalid @enderror"
               placeholder="رمز عبور جدید *" name="password" required autocomplete="new-password">

        <input id="password-confirm" type="password" class="txt txt-l @error('password') is-invalid @enderror"
               placeholder="تایید رمز عبور جدید *" name="password_confirmation" required autocomplete="new-password">
        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror

        <span class="rules">رمز عبور باید حداقل ۶ کاراکتر و ترکیبیاز حروف بزرگ،
                حروف کوچک، اعدادو کاراکترهای غیر الفبا مانند !@#$%^&*()باشد.</span>
        <br>
        <button class="btn continue-btn" autofocus>به روزرسانی رمز عبور</button>

    </form>
@endsection
