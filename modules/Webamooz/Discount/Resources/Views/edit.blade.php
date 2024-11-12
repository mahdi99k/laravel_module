@extends('Dashboard::master')

@section('title' , 'ویرایش تخفیف')
@section('breadcrumb')
    <li><a href="{{ route('discounts.index') }}" title="تخفیف ها">تخفیف ها</a></li>
    <li><a href="{{ route('discounts.edit' , $discount->id) }}" title="ویرایش تخفیف">ویرایش تخفیف</a></li>
@endsection

@section('content')
    <div class="main-content padding-0 discounts">
        <div class="row no-gutters  ">
            <div class="col-6 offset-3 bg-white margin_auto">
                <p class="box__title">ویرایش کد تخفیف</p>
                <form action="{{ route('discounts.update' , $discount->id) }}" method="post" class="padding-30">
                    @csrf
                    @method('PATCH')

                    <x-input type="text" placeholder="کد تخفیف" name="code" value="{{ $discount->code }}"/>
                    <x-input type="number" placeholder="درصد تخفیف" name="percent" required  value="{{ $discount->percent }}"/>
                    <x-input type="number" placeholder="محدودیت افراد" name="usage_limitation"  value="{{ $discount->usage_limitation }}"/>
                    <x-input type="text" placeholder="محدودیت زمانی به ساعت" name="expire_at" id="expire_at"
                             value="{{ $discount->expire_at ? str_replace('-' , '/' ,\App\Helper\Generate::createFromCarbon($discount->expire_at)) : null }}"/>

                    <p class="box__title mt-5">این تخفیف برای</p>
                    <div class="notificationGroup">
                        <input id="discounts-field-1" class="discounts-field-pn" name="type" type="radio" value="all"
                               @if($discount->type == \Webamooz\Discount\Models\Discount::TYPE_ALL) checked @endif>
                        <label for="discounts-field-1">همه دوره ها</label>
                    </div>

                    <div class="notificationGroup">
                        <input id="discounts-field-2" class="discounts-field-pn" name="type" type="radio" value="special"
                        @if($discount->type == \Webamooz\Discount\Models\Discount::TYPE_SPECIAL) checked @endif>
                        <label for="discounts-field-2">دوره خاص</label>
                    </div>

                    <div id="selectCourseContainer" class="{{ $discount->type == \Webamooz\Discount\Models\Discount::TYPE_ALL ? 'd-none' : '' }}" >
                        <select class="mySelect2" name="courses[]" multiple>
                            @foreach($courses as $course)
                                <option {{ $discount->courses->contains($course->id) ? 'selected' : '' }} value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <x-input type="text" placeholder="لینک اطلاعات بیشتر" name="link" value="{{ $discount->link }}"/>
                    <x-input type="text" placeholder="توضیحات" class="margin-bottom-15" name="description" value="{{ $discount->description }}"/>

                    <button type="submit" class="btn btn-webamooz_net mt-5">به روزرسانی</button>
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
