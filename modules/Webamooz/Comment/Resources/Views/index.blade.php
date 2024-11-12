@extends('Dashboard::master')

@section('title' , 'لیست نظرات')
@section('breadcrumb')
    <li><a href="{{ route('comments.index') }}">نظرات</a></li>
@endsection

@section('content')
    <div class="main-content">
        <div class="tab__box">
            <div class="tab__items">
                <a class="tab__item @if(request()->status == '') is-active @endif" href="{{ route('comments.index') }}"> همه نظرات</a>

                @can(\Webamooz\RolePermissions\Model\Permission::PERMISSION_MANAGE_COMMENTS)
                    <a class="tab__item @if(request()->status == 'rejected') is-active @endif"
                       href="{{ route('comments.index') }}?status={{ \Webamooz\Comment\Models\Comment::STATUS_REJECTED }}" >نظرات رد شده</a>

                    <a class="tab__item @if(request()->status == 'approved') is-active @endif"
                       href="{{ route('comments.index') }}?status={{ \Webamooz\Comment\Models\Comment::STATUS_APPROVED }}" >نظرات تاییده شده</a>

                    <a class="tab__item @if(request()->status == 'new') is-active @endif"
                       href="{{ route('comments.index') }}?status={{ \Webamooz\Comment\Models\Comment::STATUS_NEW }}" >نظرات جدید</a>
                @endcan
            </div>
        </div>
        <div class="bg-white padding-20">
            <div class="t-header-search">
                <form action="{{ route('comments.index') }}" method="GET">
                    <div class="t-header-searchbox font-size-13">
                        <input type="text" class="text search-input__box font-size-13" placeholder="جستجوی در نظرات">
                        <div class="t-header-search-content ">
                            <input type="text" class="text" placeholder="قسمتی از متن" name="body" value="{{ request()->body }}">
                            <input type="text" class="text" placeholder="ایمیل" name="email" value="{{ request()->email }}" >
                            <input type="text" class="text margin-bottom-20" placeholder="نام و نام خانوادگی" name="name" value="{{ request()->name }}" >
                            <button type="submit" class="btn btn-webamooz_net mt-5">جستجو</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="table__box">
            <table class="table">
                <thead role="rowgroup">
                <tr role="row" class="title-row">
                    <th>شناسه</th>
                    <th>ارسال کننده</th>
                    <th>برای</th>
                    <th>دیدگاه</th>
                    <th>تاریخ</th>
                    <th>تعداد پاسخ ها</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($comments as $index => $comment)
                    <tr role="row">
                        <td><a href="">{{ $comment->id}}</a></td>
                        <td><a href="">{{ $comment->user->name }}</a></td>
                        <td><a href="">{{ $comment->commentable->title }}</a></td>
                        <td>{{ \Illuminate\Support\Str::limit($comment->body , 32) }}</td>
                        <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($comment->created_at) }}</td>
                        <td>{{ $comment->comments()->count() }} ({{ $comment->not_approved_comments_count }})</td>
                        <td class="confirmation_status {{ $comment->getStatusCssClass() }}"
                            style="font-weight: bold !important;">@lang($comment->status)</td>
                        <td>
                            <a href="{{ route('comments.show' , $comment->id) }}" class="item-eye mlg-15 btn_warning_customize" title="مشاهده"></a>
                            @if(auth()->user()->hasAnyPermission([\Webamooz\RolePermissions\Model\Permission::PERMISSION_MANAGE_COMMENTS ,                                                                                       \Webamooz\RolePermissions\Model\Permission::PERMISSION_SUPER_ADMIN]))
                                <a href="" class="item-delete mlg-15 btn_red_customize" title="حذف" onclick="deleteItem(event , '{{ route('comments.destroy' , $comment->id) }}')"></a>
                                <a href="" class="item-confirm mlg-15 btn_success_customize" title="تایید شده" onclick="updateConfirmationStatus(event ,
                                    '{{ route('comments.accept' , $comment->id) }}' , 'آیا از تایید این نظر اطمینان دارید؟' ,'تایید شده','confirmation_status')"></a>
                                <a href="" class="item-reject mlg-15 btn_red_customize" title="رد شده" onclick="updateConfirmationStatus(event,
                                    '{{ route('comments.reject' , $comment->id) }}' , 'آیا از رد این نظر اطمینان دارد؟' ,'رد شده', 'confirmation_status')"></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $comments->links() }}
        </div>
    </div>
    </div>
@endsection
