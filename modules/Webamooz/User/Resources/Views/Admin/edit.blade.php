@extends('Dashboard::master')

@section('title' , 'ویرایش کاربر')
@section('breadcrumb')
    <li><a href="{{ route('users.index') }}" title="کاربر ها">کاربر ها</a></li>
    <li><a href="#" title="ویرایش کاربر">ویرایش کاربر</a></li>
@endsection


@section('content')
    <div class="row no-gutters bg-white margin-bottom-20">
        <div class="col-12">
            <p class="box__title">ویرایش کاربر</p>

            <form action="{{ route('users.update' , $user->id) }}" method="post" class="padding-30" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <x-input type="text" name="name" placeholder="عنوان کاربر"  required value="{{ $user->name }}"/>
                <x-input type="text" name="email" placeholder="ایمیل کاربر" required value="{{ $user->email }}"/>
                <x-input type="text" name="username" placeholder="نام کاربری" value="{{ $user->username }}"/>
                <x-input type="text" name="mobile" placeholder="موبایل کاربر" value="{{ $user->mobile }}"/>
                <x-input type="text" name="head_line" placeholder="عنوان" value="{{ $user->head_line }}"/>
                <x-input type="text" name="telegram" placeholder="تلگرام" value="{{ $user->telegram }}"/>

                <x-select name="status" required>
                    <option value="">وضعیت حساب</option>
                    @foreach(\Webamooz\User\Models\User::$statuses as $status)
                        <option value="{{ $status }}" @if($status == $user->status) selected @endif>@lang($status)</option>
                    @endforeach
                </x-select>

                <x-select name="role">
                    <option value="">یک نقش کاربری انتخاب کنید</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>@lang($role->name)</option>
                    @endforeach
                </x-select>

                <x-file name="image" placeholder="آپلود پروفایل کاربر" :value="$user->image" />  {{-- value="{{ $user->media->files[300] }}" --}}
                <x-input type="password" name="password" placeholder="رمز عبور جدید" />

                <x-text-area name="bio" placeholder="بیوگرافی کاربر" value="{{ $user->bio }}" />

                <button type="submit" class="btn btn-webamooz_net mt-4">به روزرسانی</button>

            </form>
        </div>
    </div>

    <div class="row no-gutters">
        <div class="col-6 margin-left-10 margin-bottom-20">
            <p class="box__title">درحال یادگیری</p>
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                    <tr role="row" class="title-row">
                        <th>شناسه</th>
                        <th>نام دوره</th>
                        <th>نام مدرس</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr role="row" class="">
                        <td><a href="">1</a></td>
                        <td><a href="">دوره لاراول</a></td>
                        <td><a href="">صیاد اعظمی</a></td>
                    </tr>
                    <tr role="row" class="">
                        <td><a href="">1</a></td>
                        <td><a href="">دوره لاراول</a></td>
                        <td><a href="">صیاد اعظمی</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-6 margin-bottom-20">
            <p class="box__title">دوره های مدرس</p>
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                    <tr role="row" class="title-row">
                        <th>شناسه</th>
                        <th>نام دوره</th>
                        <th>نام مدرس</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($user->courses as $course)
                        <tr role="row" class="">
                            <td><a href="">{{ $course->id }}</a></td>
                            <td><a href="">{{ $course->title }}</a></td>
                            <td><a href="">{{ $course->teacher->name }}</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('panel/js/tagsInput.js') }}"></script>
    <script>
        @include('Common::layouts.feedbacks')
    </script>
@endsection
