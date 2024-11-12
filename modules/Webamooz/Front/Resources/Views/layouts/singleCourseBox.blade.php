<div class="col">
    <a href="{{ $courseItem->path() }}">
        <div class="course-status">@lang($courseItem->status)</div>
        @if ($courseItem->getDiscountPercent() && $courseItem->price > 0)
            <div class="discountBadge"><p>{{ $courseItem->getDiscountPercent() }}%</p>تخفیف</div>
        @endif
        <div class="card-img"><img src="{{ $courseItem->media->thumb }}" alt="{{ $courseItem->title }}"></div>
        <div class="card-title"><h2>{{ $courseItem->title }}</h2></div>
        <div class="card-body">
            <img src="{{ $courseItem->teacher->thumb }}" alt="{{ $courseItem->teacher->name }}">
            <span>{{ $courseItem->teacher->name }}</span>
        </div>
        <div class="card-details">
            <div class="time">{{ $courseItem->formattedDurationTimeLesson() }}</div>
            <div class="price">
                @if ($courseItem->getDiscountPercent() && $courseItem->price > 0)
                    <div class="discountPrice">{{ number_format($courseItem->price) }}</div>
                @endif

                @if ($courseItem->price == 0)
                    <div class="endPrice">رایگان</div>
                @else
                    <div class="endPrice">{{ number_format($courseItem->getFinalPrice()) }}</div>
                @endif
            </div>
        </div>
    </a>
</div>
