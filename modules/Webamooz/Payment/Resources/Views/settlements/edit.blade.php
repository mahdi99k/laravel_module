@extends('Dashboard::master')

@section('title' , 'ویرایش درخواست تسویه حساب')
@section('breadcrumb')
    <li><a href="{{ route('settlements.index') }}" title="تسویه حساب ها">تسویه حساب ها</a></li>
    <li><a href="#" title="ویرایش درخواست تسویه حساب جدید">ویرایش درخواست تسویه حساب جدید</a></li>
@endsection

@section('content')
    <div class="main-content">
        <form action="{{ route('settlements.update' , $settlement->id) }}" method="POST" class="padding-30 bg-white font-size-14">
            @csrf
            @method('PATCH')

            <x-input type="text" name="from[name]" placeholder="نام صاحب حساب فرستنده" class="text"
                     value="{{ is_array($settlement->from) && array_key_exists('name' , $settlement->from) ? $settlement->from['name'] : '' }}" />

            <x-input type="text" name="from[cart]" placeholder="شماره کارت فرستنده" class="text"
                     value="{{ is_array($settlement->from) && array_key_exists('cart' , $settlement->from) ? $settlement->from['cart'] : '' }}" />

            <x-input type="text" name="to[name]" placeholder="نام صاحب حساب گیرنده" class="text" required
                     value="{{ is_array($settlement->to) && array_key_exists('name' , $settlement->to) ? $settlement->to['name'] : '' }}" />

            <x-input type="text" name="to[cart]" placeholder="شماره کارت گیرنده" class="text" required
                     value="{{ is_array($settlement->to) && array_key_exists('cart' , $settlement->to) ? $settlement->to['cart'] : '' }}" />

            <x-input type="text" name="amount" placeholder="مبلغ به تومان" class="text" value="{{ $settlement->amount }}" readonly />

            <x-select name="status">
                @foreach (\Webamooz\Payment\Models\Settlement::$statuses as $status)
                    <option @if ($settlement->status === $status) selected @endif value="{{ $status }}">@lang($status)</option>
                @endforeach
            </x-select>

            <div class="row no-gutters border-2 margin-bottom-15 mt-5 text-center">
                <div class="w-50 padding-20 w-50">باقی مانده حساب :‌</div>
                <div class="bg-fafafa padding-20 w-50"> {{ number_format($settlement->user->balance) }} تومان </div>
            </div>

            <button type="submit" class="btn btn-webamooz_net">به روزرسانی</button>
        </form>
    </div>
@endsection
