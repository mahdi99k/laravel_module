@extends('User::Front.master')

@section('title' , 'بازیابی رمز عبور')

@section('content')
    <form action="{{ route('password.update') }}" class="form" method="post">
        @csrf
        <a class="account-logo" href="/">
            <img src="img/weblogo.png" alt="">
        </a>
        <div class="form-content form-account">
            <input type="email" name="email" class="txt-l txt" placeholder="ایمیل">
            <br>
            <button class="btn btn-recoverpass">بازیابی</button>
        </div>
        <div class="form-footer">
            <a href="login">صفحه ورود</a>
        </div>
    </form>
@endsection



