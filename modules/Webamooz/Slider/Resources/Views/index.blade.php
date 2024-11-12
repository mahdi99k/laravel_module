@extends('Dashboard::master')

@section('title' , 'لیست اسلاید ها')
@section('breadcrumb')
<li><a href="#" title="اسلاید ها">اسلاید ها</a></li>
@endsection

@section('content')

<div class="row no-gutters">
    <div class="col-8 margin-left-10 margin-bottom-15 border-radius-3">
        <p class="box__title">اسلاید ها</p>
        <div class="table__box">
            <table class="table">
                <thead role="rowgroup">
                <tr role="row" class="title-row">
                    <th>شناسه</th>
                    <th>کاربر</th>
                    <th>تصویر</th>
                    <th>الویت</th>
                    <th>لینک</th>
                    <th>وضعیت نمایش</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($slides as $index => $slide)
                <tr role="row" class="">
                    <td>{{ $slides->firstItem() + $index }}</td>
                    <td><a href="">{{ $slide->user->name }}</a></td>
                    <td><img src="{{ $slide->media->thumb }}" alt="" width="80"></td>
                    <td>{{ $slide->priority }}</td>
                    <td>{{ $slide->link }}</td>
                    <td>{{ ($slide->status) ? 'فعال' : 'غیر فعال' }}</td>
                    <td>
                        <a href="" class="item-delete mlg-15 btn_red_customize" title="حذف"
                           onclick="deleteItem(event , '{{ route('slides.destroy' , $slide->id) }}')"></a>
                        <a href="" class="item-eye mlg-15 btn_warning_customize" title="مشاهده"></a>
                        <a href="{{ route('slides.edit' , $slide->id) }}" class="item-edit btn_info_customize" title="ویرایش"></a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            {{ $slides->links() }}
        </div>
    </div>

    <div class="col-4 bg-white">
        @includeIf('Slides::create')
    </div>
</div>

@endsection
