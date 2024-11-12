@extends('Dashboard::master')

@section('title' , 'لیست تیکت ها')
@section('breadcrumb')
    <li><a href="{{ route('tickets.index') }}">تیکت ها</a></li>
    <li><a href="{{ route('tickets.create') }}" class="is-active">ارسال تیکت جدید</a></li>
@endsection

@section('content')
    <div class="main-content tickets">
        <div class="tab__box">
            <div class="tab__items">

                <a class="tab__item {{ request()->status == '' ? 'is-active' : '' }}" href="{{ route('tickets.index') }}">همه تیکت ها</a>

                @can(\Webamooz\RolePermissions\Model\Permission::PERMISSION_MANAGE_TICKETS)
                    <a class="tab__item {{ request()->status == 'open' ? 'is-active' : '' }}"
                       href="?{{ request()->getQueryString() }}&status={{ \Webamooz\Ticket\Models\Ticket::STATUS_OPEN }}">جدید ها (خوانده نشده)</a>

                    <a class="tab__item {{ request()->status == 'replied' ? 'is-active' : '' }}"
                       href="?{{ request()->getQueryString() }}&status={{ \Webamooz\Ticket\Models\Ticket::STATUS_REPLIED }}">پاسخ داده شده ها</a>

                    <a class="tab__item {{ request()->status == 'close' ? 'is-active' : '' }}"
                       href="?{{ request()->getQueryString() }}&status={{ \Webamooz\Ticket\Models\Ticket::STATUS_CLOSE }}">بسته شده</a>
                @endcan

                <a class="tab__item" href="{{ route('tickets.create') }}">ارسال تیکت جدید</a>

            </div>
        </div>
        @can(\Webamooz\RolePermissions\Model\Permission::PERMISSION_MANAGE_TICKETS)
            <div class="bg-white padding-20">
                <div class="t-header-search">
                    <form action="{{ route('tickets.index') }}" method="GET">
                        <div class="t-header-searchbox font-size-13">
                            <input type="text" class="text search-input__box font-size-13" placeholder="جستجوی در تیکت ها">
                            <div class="t-header-search-content ">
                                <input type="text" class="text" placeholder="عنوان تیکت" name="title" value="{{ request()->title }}">
                                <input type="text" class="text" placeholder="ایمیل" name="email" value="{{ request()->email }}">
                                <input type="text" class="text " placeholder="نام و نام خانوادگی" name="name" value="{{ request()->name }}">
                                <input type="text" class="text mb-2-custom" placeholder="تاریخ مثال : 1403/06/08" name="date" value="{{ request()->date }}">
                                <button type="submit" class="btn btn-webamooz_net">جستجو</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endcan

        <div class="table__box">
            <table class="table">
                <thead role="rowgroup">
                <tr role="row" class="title-row">
                    <th>شناسه</th>
                    <th>عنوان</th>
                    <th>نام ارسال کننده</th>
                    <th>ایمیل ارسال کننده</th>
                    <th>آخرین بروزرسانی</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>

                @foreach($tickets as $index => $ticket)
                    <tr role="row">
                        <td><a href="">{{ $tickets->firstItem() + $index }}</a></td>
                        <td><a href="{{ route('tickets.show' , $ticket->id)  }}"
                               class="text-info_custom">{{ $ticket->title }}</a></td>
                        <td>{{ $ticket->user->name }}</td>
                        <td>{{ $ticket->user->email }}</td>
                        <td>{{ str_replace('-' , '/' , \Morilog\Jalali\Jalalian::fromCarbon($ticket->updated_at)) }}</td>
                        <td class="text-info">@lang($ticket->status)</td>
                        <td>
                            @can(\Webamooz\RolePermissions\Model\Permission::PERMISSION_MANAGE_TICKETS)
                                <a href="" onclick="deleteItem(event, '{{ route('tickets.destroy' , $ticket->id) }}')"
                                   class="item-delete mlg-15 btn_red_customize" title="حذف"></a>
                            @endcan
                            <a href="{{ route('tickets.close' , $ticket->id) }}"
                               onclick="return confirm('آیا می خواهید تیکت را ببندید?')"
                               class="item-reject mlg-15 btn_red_customize" title="بستن تیکت"></a>
                            <a href="{{ route('tickets.show' ,$ticket->id) }}" target="_blank"
                               class="item-eye mlg-15 btn_warning_customize" title="مشاهده"></a>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
