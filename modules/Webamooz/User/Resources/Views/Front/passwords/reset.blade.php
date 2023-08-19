@extends('User::Front.master')
@section('title' , 'بازیابی رمز عبور')



@section('content')
    <form method="POST" action="{{ route('password.update') }}" class="form">
        <a class="account-logo" href="/">
            <img src="/img/weblogo.png" alt="">
        </a>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <input id="email" type="email" class="txt txt-l @error('email') is-invalid @enderror" name="email"
               value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="ایمیل">

        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror

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


{{--

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

--}}
