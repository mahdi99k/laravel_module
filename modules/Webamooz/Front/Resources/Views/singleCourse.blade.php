@extends('Front::layouts.master')
@section('title' , 'نمایش جزییات دوره')

@section('content')
    <main id="single">
        <div class="content">

            <div class="container">
                <article class="article mr-152">

                    @includeIf('Front::layouts.header-ads')

                    <div class="h-t">
                        <h1 class="title">{{ $course->title }}</h1>
                        <div class="breadcrumb">
                            <ul>
                                <li><a href="/" title="خانه">خانه</a></li>
                                @if ($course->category->parentCategory)
                                    <li><a href="{{ $course->category->parentCategory->path() }}"
                                           title="{{ $course->category->parentCategory->title }}">
                                            {{ $course->category->parentCategory->title }}</a></li>
                                @endif
                                <li><a href="{{ $course->category->path() }}"
                                       title="{{ $course->category->title }}">{{ $course->category->title }}</a></li>
                            </ul>
                        </div>
                    </div>

                </article>
            </div>


            <div class="main-row container">
                <div class="sidebar-right">
                    <div class="sidebar-sticky" style="top: 104px;">
                        <div class="product-info-box">
                            <div class="discountBadge d-none">
                                <p>45%</p>تخفیف
                            </div>

                            @auth
                                @if (auth()->id() == $course->teacher_id)
                                    <p class="mycourse ">شما مدرس این دوره هستید</p>
                                @elseif(auth()->user()->can("download" , $course))
                                    <p class="mycourse">شما این دوره رو خریداری کرده اید</p>
                                @else
                                    <div class="sell_course">
                                        <strong>قیمت :</strong>
                                        @if ($course->getDiscountPercent())
                                            <del class="discount-Price">{{ number_format($course->price) }}</del>
                                        @endif
                                        <p class="price">
                                            <span class="woocommerce-Price-amount amount">{{ $course->getFormattedFinalPrice()  }}
                                                <span class="woocommerce-Price-currencySymbol">تومان</span>
                                            </span>
                                        </p>
                                    </div>

                                    <button class="btn buy btn-buy">خرید دوره</button>
                                @endif

                            @else

                                <div class="sell_course">
                                    <strong>قیمت :</strong>
                                    @if ($course->getDiscountPercent())
                                        <del class="discount-Price">{{ number_format($course->price) }}</del>
                                    @endif
                                    <p class="price">
                                        <span class="woocommerce-Price-amount amount">{{ number_format($course->price) }}
                                            <span class="woocommerce-Price-currencySymbol">تومان</span>
                                        </span>
                                    </p>
                                </div>
                                <p>جهت خرید دوره ابتدا در سایت وارد شوید</p>
                                <a href="{{ route('login') }}" class="btn text-white w-100">ورود به سایت</a>

                            @endauth


                            <div class="average-rating-sidebar">
                                <div class="rating-stars">
                                    <div class="slider-rating" data-title="خیلی خوب">
                                        <span class="slider-rating-span slider-rating-span-100" data-value="100%"
                                              data-title="خیلی خوب"></span>
                                        <span class="slider-rating-span slider-rating-span-80" data-value="80%"
                                              data-title="خوب"></span>
                                        <span class="slider-rating-span slider-rating-span-60" data-value="60%"
                                              data-title="معمولی"></span>
                                        <span class="slider-rating-span slider-rating-span-40" data-value="40%"
                                              data-title="بد"></span>
                                        <span class="slider-rating-span slider-rating-span-20" data-value="20%"
                                              data-title="خیلی بد"></span>
                                        <div class="star-fill"></div>
                                    </div>
                                </div>

                                <div class="average-rating-number">
                                    <span class="title-rate title-rate1">امتیاز</span>
                                    <div class="schema-stars">
                                        <span class="value-rate text-message"> 4 </span>
                                        <span class="title-rate">از</span>
                                        <span class="value-rate"> 555 </span>
                                        <span class="title-rate">رأی</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="product-info-box">
                            <div class="product-meta-info-list">
                                <div class="total_sales">
                                    تعداد دانشجو : <span>{{ count($course->students) }}</span>
                                </div>
                                <div class="meta-info-unit one">
                                    <span class="title">تعداد جلسات منتشر شده :  </span>
                                    <span class="vlaue">{{ $course->lessonCount() }}</span>
                                </div>
                                <div class="meta-info-unit two">
                                    <span class="title">مدت زمان دوره تا الان : </span>
                                    <span class="vlaue">{{ $course->formattedDurationTimeLesson() }}</span>
                                </div>
                                <div class="meta-info-unit three">
                                    <span class="title">مدت زمان کل دوره : </span>
                                    <span class="vlaue">-</span>
                                </div>
                                <div class="meta-info-unit four">
                                    <span class="title">مدرس دوره : </span>
                                    <span class="vlaue">{{ $course->teacher->name }}</span>
                                </div>
                                <div class="meta-info-unit five">
                                    <span class="title">وضعیت دوره : </span>
                                    <span class="vlaue">@lang($course->status)</span>
                                </div>
                                <div class="meta-info-unit six">
                                    <span class="title">پشتیبانی : </span>
                                    <span class="vlaue">دارد</span>
                                </div>
                            </div>
                        </div>
                        <div class="course-teacher-details">
                            <div class="top-part">
                                <a href="{{ route('singleTutors' , $course->teacher->username) }}">
                                    <img alt="{{ $course->teacher->name }}" class="img-fluid lazyloaded"
                                         src="img/profile.jpg" loading="lazy">
                                    <noscript>
                                        <img class="img-fluid" src="{{ $course->teacher->thumb }}"
                                             alt="{{ $course->teacher->name }}">
                                    </noscript>
                                </a>
                                <div class="name">
                                    <a href="{{ route('singleTutors' , $course->teacher->username) }}" class="btn-link">
                                        <h6>{{ $course->teacher->name }}</h6>
                                    </a>
                                    <span class="job-title">{{ $course->teacher->head_line }}</span>
                                </div>
                            </div>
                            <div class="job-content">
                                {{--  {{ $course->teacher->bio }}  --}}
                            </div>
                        </div>
                        <div class="short-link">
                            <div class="">
                                <span>لینک کوتاه</span>
                                <input class="short--link" value="{{ $course->shortUrl() }}">
                                <a href="{{ $course->shortUrl() }}" class="short-link-a"
                                   data-link="{{ $course->shortUrl() }}"></a>
                            </div>
                        </div>


                        @includeIf('Front::layouts.sidebar-banners')  {{-- channels telegram --}}

                    </div>
                </div>
                <div class="content-left">
                    @if ($lesson)
                        @if ($lesson->media->type == 'video')
                            <div class="preview">
                                <video width="100%" controls="">
                                    <source src="{{ $lesson->downloadLink() }}" type="video/mp4">
                                </video>
                            </div>
                        @endif
                        <a href="{{ $lesson->downloadLink() }}" class="episode-download"> دانلود این قسمت
                            (قسمت {{ $lesson->number }})</a>
                    @endif

                    <div class="course-description">
                        <div class="course-description-title">توضیحات دوره</div>
                        <p>{!! $course->body !!}</p>


                        <div class="tags">
                            <ul>
                                <li><a href="">ری اکت</a></li>
                                <li><a href="">reactjs</a></li>
                                <li><a href="">جاوااسکریپت</a></li>
                                <li><a href="">javascript</a></li>
                                <li><a href="">reactjs چیست</a></li>
                            </ul>
                        </div>
                    </div>

                    @include('Front::layouts.episodes-list')  {{-- list downlod lesson courses --}}

                </div>
            </div>

        </div>

        {{-- modal buy course --}}
        <div id="Modal3" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <p>کد تخفیف را وارد کنید</p>
                    <div class="close">&times;</div>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('courses.buy' , $course->id) }}">
                        @csrf
                        <div>
                            <input type="text" name="code" id="code" class="txt" placeholder="کد تخفیف را وارد کنید">
                            <span id="response"></span>
                        </div>
                        <button type="button" class="btn i-t " onclick="checkDiscountCode()">اعمال
                            <img src="{{ asset('img/loading.gif') }}" alt="" id="loading" class="loading d-none">
                        </button>

                        <table class="table text-center text-black table-bordered table-striped">
                            <tbody>
                            <tr>
                                <th>قیمت کل دوره</th>
                                <td> {{ $course->getFormattedPrice() }} تومان</td>
                            </tr>
                            <tr>
                                <th>درصد تخفیف</th>
                                <td>
                                    <span id="discountPercent" data-value="{{ $course->getDiscountPercent() }}">
                                        {{ $course->getDiscountPercent() }}
                                    </span>%
                                </td>
                            </tr>
                            <tr>
                                <th> مبلغ تخفیف</th>
                                <td class="text-red">
                                    <span id="discountAmount" data-value="{{ $course->getDiscountAmount() }}">
                                        {{ $course->getFormattedDiscountAmount() }}</span> تومان
                                </td>
                            </tr>
                            <tr>
                                <th> قابل پرداخت</th>
                                <td class="text-blue">
                                    <span id="payableAmount" data-value="{{ $course->getFinalPrice() }}">
                                        {{ $course->getFormattedFinalPrice() }}</span> تومان
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn i-t ">پرداخت آنلاین</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Comments --}}
        @includeIf('Front::comments.index' ,['commentable' => $course])


    </main>
@endsection


@section('js')
    <script src="{{ asset('/js/modal.js') }}"></script>
    <script>
        function checkDiscountCode() {

            $("#loading").removeClass('d-none');
            const code = $("#code").val();
            const url = "{{ route('discounts.check' , ['code' , $course->id]) }}";

            $.get(url.replace("code", code))
                .done(function (data) {  //data -> Controller check
                    $("#discountPercent").text(parseInt($("#discountPercent").attr('data-value')) + data.discountPercent);  //درصد تخفیف سراسری قبلی با درصد تخفیف جدیدی جمع
                    $("#discountAmount").text(parseInt($("#discountAmount").attr('data-value')) + data.discountAmount);  //مبلغ تخفیف سراسری با مبلغ کد تخفیف جمع
                    $("#payableAmount").text(parseInt($("#payableAmount").attr('data-value')) - data.discountAmount);  //قیمت نهایی تخفیف سراسری از کد تخفیف منفی
                    $("#response").text("کد تخفیف با موفقیت اعمال شد").removeClass('text-danger_custom').addClass('text-success_custom');
                })

                .fail(function (data) {
                    $("#response").text("کد وارد شده، برای این درس معتبر نیست").removeClass('text-success_custom').addClass('text-danger_custom');
                })

                .always(function () {  //میاد همیشه اجرا میش وقتی کلیک کردیم ی چند صدوم ثانیه ای نمایش میده
                    $("#loading").addClass('d-none');
                })
            /*.beforeSend(function (){  //عملیات قبل ارسال
                $("#code").val('')
            })*/;
        }
    </script>
@endsection

@section('css')
    <link rel="stylesheet" href={{ asset('/css/modal.css') }}>
@endsection

