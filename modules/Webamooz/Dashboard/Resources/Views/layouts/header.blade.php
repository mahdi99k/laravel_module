<div class="header d-flex item-center bg-white width-100 border-bottom padding-12-30">
    <div class="header__right d-flex flex-grow-1 item-center">
        <span class="bars"></span>
        <a class="header__logo" href="/"></a>
    </div>
    <div class="header__left d-flex flex-end item-center margin-top-2">
        <span class="account-balance font-size-12"> موجودی : {{ number_format(auth()->user()->balance) }} تومان</span>
        <div class="notification margin-15">
            @if(count($notifications))  <!-- $notifications -> مقدار از سرویس پروایدر گرفتیم تمام نوتیفیکشن های خوانده نشده -->
                <span class="notify_dot_active"></span>
            @endif
            <a class="notification__icon @if(count(auth()->user()->unreadNotifications)) notify_active @endif"></a>
            <div class="dropdown__notification">
                <div class="content__notification">
                    <ul class="notification">
                        @forelse ($notifications as $notification)
                        <li>
                            <a href="{{ $notification->data['url'] }}">
                                <span class="font-size-13">{{ $notification->data['message'] }}</span>
                            </a>
                        </li>
                        @empty
                        <span class="font-size-13 text-danger_custom">موردی برای نمایش وجود ندارد</span>
                        @endforelse
                    </ul>
                    @if(count(auth()->user()->unreadNotifications))
                        <a href="{{ route('notifications.markAllRead') }}" class="btn btn-webamooz_net mt-4 font-size-10">علامت زدن همه به عنوان خوانده شده</a>
                    @endif
                </div>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST" id="logout-form">
            @csrf
            <a href="{{ route('logout') }}" class="logout" title="خروج"
               onclick="event.preventDefault();document.getElementById('logout-form').submit();"></a>
        </form>
    </div>
</div>
