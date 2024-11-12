<div class="sidebar__nav border-top border-left  ">
    <span class="bars d-none padding-0-18"></span>
    <a class="header__logo  d-none" href="https://webamooz.net"></a>

    <x-user-photo />

    <ul>
        @foreach(config('sidebar.items') as $sidebarItem)
{{-- str_starts_with(request()->url(localhost:8000/categories/1/edit) == localhost:8000/categories) //مقدار اولشم یکی بود صحیح بر میگردونه برای صفحات ایجاد و ویرایش --}}
        {{--!array_key_exists('permission',$sidebarItem) ااگر سرویس پروایدر پرمیژن نداشت نمایش + یا کاربر مجوز سوپر ادمین داشت نمایش + یا دسترسی که تعیین کردین درون پروایدر --}}
                @if (!array_key_exists('permission' , $sidebarItem) ||
                    auth()->user()->hasAnyPermission($sidebarItem['permission']) ||  //hasAnyPermission -> support array or string
                    auth()->user()->hasPermissionTo(\Webamooz\RolePermissions\Model\Permission::PERMISSION_SUPER_ADMIN))  {{-- hasPermissionTo -> support string --}}

                <li class="item-li {{ $sidebarItem['icon'] }} @if(str_starts_with(request()->url() , $sidebarItem['url'])) is-active @endif ">
                    <a href="{{ $sidebarItem['url'] }}">{{ $sidebarItem['title'] }}</a></li>
                @endif
        @endforeach
    </ul>

</div>















{{--

<li class="item-li i-dashboard {{ (Request::route()->getName() == 'home') ? 'is-active' : '' }}">
    <a href="{{ route('home') }}">پیشخوان</a></li>
<li class="item-li i-courses "><a href="courses.html">دوره ها</a></li>
<li class="item-li i-users"><a href="users.html"> کاربران</a></li>
<li class="item-li i-categories {{ (Request::route()->getName() == 'categories.index') ? 'is-active' : '' }}">
    <a href="{{ route('categories.index') }}">دسته بندی ها</a></li>
<li class="item-li i-slideshow"><a href="slideshow.html">اسلایدشو</a></li>
<li class="item-li i-banners"><a href="banners.html">بنر ها</a></li>
<li class="item-li i-articles"><a href="articles.html">مقالات</a></li>
<li class="item-li i-ads"><a href="ads.html">تبلیغات</a></li>
<li class="item-li i-comments"><a href="comments.html"> نظرات</a></li>
<li class="item-li i-tickets"><a href="tickets.html"> تیکت ها</a></li>
<li class="item-li i-discounts"><a href="discounts.html">تخفیف ها</a></li>
<li class="item-li i-transactions"><a href="transactions.html">تراکنش ها</a></li>
<li class="item-li i-checkouts"><a href="checkouts.html">تسویه حساب ها</a></li>
<li class="item-li i-checkout__request "><a href="checkout-request.html">درخواست تسویه </a></li>
<li class="item-li i-my__purchases"><a href="mypurchases.html">خرید های من</a></li>
<li class="item-li i-my__peyments"><a href="mypeyments.html">پرداخت های من</a></li>
<li class="item-li i-notification__management"><a href="notification-management.html">مدیریت اطلاع رسانی</a></li>
<li class="item-li i-user__inforamtion"><a href="user-information.html">اطلاعات کاربری</a></li>

--}}
