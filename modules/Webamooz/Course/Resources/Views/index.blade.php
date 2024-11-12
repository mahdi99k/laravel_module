@extends('Dashboard::master')

@section('title' , 'لیست دوره ها')
@section('breadcrumb')
    <li><a href="#" title="دوره ها">دوره ها</a></li>
@endsection

@section('content')

    <div class="tab__box">
        <div class="tab__items">
            <a class="tab__item is-active" href="courses.html">لیست دوره ها</a>
            <a class="tab__item" href="approved.html">دوره های تایید شده</a>
            <a class="tab__item" href="new-course.html">دوره های تایید نشده</a>
            <a class="tab__item" href="{{ route('courses.create') }}">ایجاد دوره جدید</a>
        </div>
    </div>

    <div class="row no-gutters">
        <div class="col-12 margin-left-10 margin-bottom-15 border-radius-3">
            <p class="box__title">دوره ها</p>
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                    <tr role="row" class="title-row">
                        <th>تصویر</th>
                        <th>ردیف</th>
                        <th>آیدی</th>
                        <th>عنوان</th>
                        {{--<th>عنوان انگلیسی</th>--}}
                        <th>مدرس</th>
                        <th>نوع</th>
                        <th>قیمت(تومان)</th>
                        <th>درصد</th>
                        <th>توضیحات</th>
                        <th>جزییات</th>
                        <th>وضعیت</th>
                        <th>وضعیت تایید</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($courses as $course)
                        <tr role="row" class="">
                            <td width="130px"><a href=""><img src="{{ $course->media->thumb }}" width="130px"
                                                              alt=""></a></td>
                            <td><a href="">{{ $course->priority }}</a></td>
                            <td><a href="">{{ $course->id }}</a></td>
                            <td><a href="">{{ $course->title }}</a></td>
                            {{--<td>{{ $course->slug }}</td>--}}
                            <td><a href="">{{ $course->teacher->name }}</a></td>
                            <td>@lang($course->type)</td>
                            <td>{{ number_format($course->price) }}</td>
                            <td>{{ $course->percent }}%</td>
                            <td>{{ Str::limit($course->body , 200 , '...') }}</td>
                            <td><a href="{{ route('courses.details' , $course->id) }}" class="text-info">مشاهده</a></td>
                            <td class="status">@lang($course->status)</td>
                            <td class="confirmation_status">@lang($course->confirmation_status)</td>
                            <td>
                                <a href="" target="_blank" class="item-eye mlg-15 btn_warning_customize"
                                   title="مشاهده"></a>
                                <a href="{{ route('courses.edit' , $course->id) }}"
                                   class="item-edit btn_info_customize mlg-15" title="ویرایش"></a>

                                @can(\Webamooz\RolePermissions\Model\Permission::PERMISSION_MANAGE_COURSES)
                                    <a href="" class="item-delete mlg-15 btn_red_customize" title="حذف"
                                       onclick="deleteItem(event, '{{ route('courses.destroy' ,$course->id) }}')"></a>

                                    <a href=""
                                       onclick="updateConfirmationStatus(event , '{{ route('courses.accept' , $course->id) }}' ,
                                           'آیا از تایید دوره مطمعن هستید؟' , 'تایید شده' , 'confirmation_status')"
                                       class="item-confirm mlg-15 btn_success_customize" title="تایید شده"></a>

                                    <a href="" onclick="updateConfirmationStatus(event , '{{ route('courses.reject' , $course->id) }}' ,
                                           'آیا از رد دوره مطمعن هستید؟' , 'رد شده' , 'confirmation_status')"
                                       class="item-reject mlg-15 btn_red_customize" title="رد شده"></a>

                                    <a href=""
                                       onclick="updateConfirmationStatus(event , '{{ route('courses.lock' , $course->id) }}' ,
                                           'آیا از قفل شدن دوره مطمعن هستید؟' , 'قفل شده' , 'status')"
                                       class="item-lock mlg-15 btn_lock_customize" title="قفل شده"></a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    {{ $courses->links() }}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
