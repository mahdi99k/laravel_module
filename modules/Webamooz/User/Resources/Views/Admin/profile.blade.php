@extends('Dashboard::master')

@section('title' , 'ویرایش پروفایل')
@section('breadcrumb')
    <li><a href="{{ route('users.index') }}" title="کاربر ها">کاربر ها</a></li>
    <li><a href="#" title="ویرایش پروفایل">ویرایش پروفایل</a></li>
@endsection


@section('content')
    <div class="row no-gutters bg-white margin-bottom-20">
        <div class="col-12">
            <p class="box__title">ویرایش پروفایل</p>

            <x-user-photo />

            <form action="{{ route('users.profile.update') }}" method="post" class="padding-30">
                @csrf

                <x-input type="text" name="name" placeholder="عنوان کاربر"  required value="{{ auth()->user()->name }}"/>
                <x-input type="text" name="email" placeholder="ایمیل کاربر" required value="{{ auth()->user()->email }}"/>
                <x-input type="text" name="mobile" placeholder="موبایل کاربر" value="{{ auth()->user()->mobile }}"/>
                <x-input type="password" name="password" placeholder="رمز عبور جدید" />
                <p class="rules">رمز عبور باید حداقل ۶ کاراکتر و ترکیبی از حروف بزرگ، حروف کوچک، اعداد و کاراکترهای
                    غیر الفبا مانند <strong>!@#$%^&amp;*()</strong> باشد.</p>


                @can(\Webamooz\RolePermissions\Model\Permission::PERMISSION_TEACH)  {{-- خر کی مجوز تدریس داره نمایش بده --}}
                <x-input type="text" name="card_number" placeholder="شماره کارت بانکی" value="{{ auth()->user()->card_number }}"/>
                <x-input type="text" name="sheba" placeholder="شماره شبا بانکی" value="{{ auth()->user()->sheba }}"/>
                <x-input type="text" name="username" placeholder="نام کاربری و آدرس پروفایل" value="{{ auth()->user()->username }}"/>
                <p class="input-help text-left margin-bottom-12" dir="ltr">
                    @if (auth()->user()->username)
                    <a href="{{ auth()->user()->username }}">{{ auth()->user()->username }}</a>
                    @else
                        <a href="">username</a>
                    @endif
                </p>
                <x-input type="text" name="head_line" placeholder="عنوان" value="{{ auth()->user()->head_line }}"/>
                <x-input type="text" name="telegram" placeholder="آیدی شما در تلگرام برای دریافت نوتیفیکیشن" value="{{ auth()->user()->telegram }}"/>
                    <x-text-area name="bio" placeholder="درباره من مخصوص مدرسین" value="{{ auth()->user()->bio }}" />
                @endcan


                <button type="submit" class="btn btn-webamooz_net mt-4">بروزرسانی پروفایل</button>

            </form>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('panel/js/tagsInput.js') }}"></script>
    <script>
        @include('Common::layouts.feedbacks')
    </script>
@endsection
