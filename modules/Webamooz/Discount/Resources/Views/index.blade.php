@extends('Dashboard::master')

@section('title' , 'تخفیف ها')
@section('breadcrumb')
    <li><a href="{{ route('discounts.index') }}" title="تخفیف ها">تخفیف ها</a></li>
@endsection

@section('content')
    <div class="main-content padding-0 discounts">
        <div class="row no-gutters  ">
            <div class="col-8 margin-left-10 margin-bottom-15 border-radius-3">
                <p class="box__title">تخفیف ها</p>
                <div class="table__box">
                    <div class="table-box">
                        <table class="table">
                            <thead role="rowgroup">
                            <tr role="row" class="title-row">
                                <th>شناسه</th>
                                <th>کد تخفیف</th>
                                <th>درصد تخفیف</th>
                                <th>محدودیت افراد</th>
                                <th>محدودیت زمانی</th>
                                <th>توضیحات</th>
                                <th>استفاده شده</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($discounts as $index => $discount)
                                <tr role="row" class="">
                                    <td><a href="">{{ $discounts->firstItem() + $index }}</a></td>
                                    <td>{{ $discount->code ?? '-' }}</td>
                                    <td><a href="">{{ $discount->percent }} % </a><span class="text-info_custom">@lang($discount->type)</span></td>
                                    <td>{{ $discount->usage_limitation === null ? 'نامحدود' : $discount->usage_limitation }}</td>
                                    <td>{{ $discount->expire_at ? \App\Helper\Generate::createFromCarbon($discount->expire_at) : 'بدون تاریخ انقضا' }}</td>
                                    <td>{{ $discount->description ?? '-' }}</td>
                                    <td>{{ $discount->uses }} نفر</td>
                                    <td>
                                        <a href="" class="item-delete mlg-15 btn_red_customize" title="حذف"
                                           onclick="deleteItem(event , '{{ route('discounts.destroy' , $discount->id) }}')"></a>
                                        <a href="{{ route('discounts.edit' , $discount->id) }}" class="item-edit btn_info_customize mlg-15" title="ویرایش"></a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-4 bg-white">
                <p class="box__title">ایجاد تخفیف جدید</p>
                <form action="{{ route('discounts.store') }}" method="post" class="padding-30">
                    @csrf

                    <x-input type="text" placeholder="کد تخفیف" name="code"/>
                    <x-input type="number" placeholder="درصد تخفیف" name="percent" required/>
                    <x-input type="number" placeholder="محدودیت افراد" name="usage_limitation"/>
                    <x-input type="text" placeholder="محدودیت زمانی به ساعت" name="expire_at" id="expire_at"/>

                    <p class="box__title mt-5">این تخفیف برای</p>
                    <x-validation-error field="type" />  {{-- show error --}}

                    <div class="notificationGroup">
                        <input id="discounts-field-1" class="discounts-field-pn" name="type" type="radio" value="all">
                        <label for="discounts-field-1">همه دوره ها</label>
                    </div>

                    <div class="notificationGroup">
                        <input id="discounts-field-2" class="discounts-field-pn" name="type" type="radio" value="special">
                        <label for="discounts-field-2">دوره خاص</label>
                    </div>

                    <div id="selectCourseContainer" class="d-none">
                        <select class="mySelect2" name="courses[]" multiple>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <x-input type="text" placeholder="لینک اطلاعات بیشتر" name="link"/>
                    <x-input type="text" placeholder="توضیحات" class="margin-bottom-15" name="description"/>

                    <button type="submit" class="btn btn-webamooz_net mt-5">اضافه کردن</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link type="text/css" rel="stylesheet"
          href="{{ asset('assets/persianDatePicker/css/persianDatepicker-default.css') }}"/>
    <link type="text/css" rel="stylesheet"
          href="{{ asset('assets/persianDatePicker/css/persianDatepicker-dark.css') }}"/>
    <link type="text/css" rel="stylesheet"
          href="{{ asset('assets/persianDatePicker/css/persianDatepicker-latoja.css') }}"/>
    <link type="text/css" rel="stylesheet"
          href="{{ asset('assets/persianDatePicker/css/persianDatepicker-lightorang.css') }}"/>
    <link type="text/css" rel="stylesheet"
          href="{{ asset('assets/persianDatePicker/css/persianDatepicker-melon.css') }}"/>
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/select2/css/selet2.min.css') }}"/>
@endsection

@section('js')
    <!----- Persian Date Picker ----->
    <script src="{{ asset('assets/select2/js/select2.min.js') }}"></script>
    <script>
        $('.mySelect2').select2({
            placeholder: "یک یا چند دوره را انتخاب کنید..."
        });
    </script>

    <!----- Persian Date Picker ----->
    <script src="{{ asset('assets/persianDatePicker/js/persianDatepicker.min.js') }}"></script>
    {{----- Use Default Theme With Javascript -----}}
    {{--<script type="text/javascript">
        $(function() {
            $("#expire_at").persianDatepicker({
                formatDate: "YYYY/MM/DD hh:mm",
            });
        });
    </script>--}}
    {{----- Use Other Theme And Customize With JQuery -----}}
    <script>
        $("#expire_at").persianDatepicker({
            months: ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور", "مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"],
            dowTitle: ["شنبه", "یکشنبه", "دوشنبه", "سه شنبه", "چهارشنبه", "پنج شنبه", "جمعه"],
            shortDowTitle: ["ش", "ی", "د", "س", "چ", "پ", "ج"],
            showGregorianDate: !1,
            persianNumbers: !0,
            formatDate: "YYYY/0M/0D hh:mm:ss",  //0D -> two number دو رقمی نمایش روز + OM -> قبلش صفر بزاریم ماه دورقمی نمایش
            selectedBefore: !1,
            selectedDate: null,
            startDate: null,
            endDate: null,
            prevArrow: '\u25c4',
            nextArrow: '\u25ba',
            theme: 'dark',
            alwaysShow: !1,
            selectableYears: null,
            selectableMonths: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            //----- Customize the size
            cellWidth: 28, // by px
            cellHeight: 23, // by px
            fontSize: 14, // by px
            isRTL: !1,
            calendarPosition: {
                x: 0,
                y: 0,
            },
            onShow: function () {
            },
            onHide: function () {
            },
            onSelect: function () {
            },
            onRender: function () {
            }
        });
    </script>
@endsection
