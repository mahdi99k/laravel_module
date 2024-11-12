@extends('Dashboard::master')

@section('title' , 'لیست کاربران')
@section('breadcrumb')
    <li><a href="#" title="کاربران">کاربران</a></li>
@endsection

@section('content')

    <div class="row no-gutters">
        <div class="col-12 margin-left-10 margin-bottom-15 border-radius-3">
            <p class="box__title">کاربران</p>
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                    <tr role="row" class="title-row">
                        <th>ردیف</th>
                        <th>شناسه</th>
                        <th>نام و نام خانوادگی</th>
                        <th>ایمیل</th>
                        <th>شماره موبایل</th>
                        <th>سطح کاربری</th>
                        <th>تاریخ عضویت</th>
                        <th>آی پی</th>
                        <th>وضعیت حساب</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($users as $index => $user)
                        <tr role="row" class="">
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->mobile }}</td>
                            <td>
                                <ul>
                                    @foreach($user->roles as $userRole)
                                        <li>@lang($userRole->name)</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $user->created_at }}</td>
                            <td>{{ $user->ip }}</td>
                            <td class="confirmation_status">{!! $user->hasVerifiedEmail()
                            ? "<span class='text-success fw-bold'>تایید شده</span>"  : "<span class='text-error fw-bold'>تایید نشده</span>" !!}</td>
                            <td>
                                <a href="" class="item-delete mlg-15 btn_red_customize" title="حذف"
                                   onclick="deleteItem(event, '{{ route('users.destroy' ,$user->id) }}')"></a>
                                <a href="{{ route('users.edit' , $user->id) }}"
                                   class="item-edit btn_info_customize mlg-15" title="ویرایش"></a>
                                <a href="" onclick="updateConfirmationStatus(event , '{{ route('users.manualVerify' , $user->id) }}' ,
                                    'آیا از تایید ایمیل کاربر مطمعن هستید؟' , 'تایید شده' , 'confirmation_status')"
                                   class="item-confirm mlg-15 btn_success_customize" title="تایید ایمیل کاربر"></a>
                            </td>
                        </tr>
                    @endforeach
                    {{ $users->links() }}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        @includeIf('Common::layouts.feedbacks')  {{-- show session flash --}}
    </script>
@endsection

