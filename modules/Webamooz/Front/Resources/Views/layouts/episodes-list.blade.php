<div class="episodes-list">
    <div class="episodes-list--title">فهرست جلسات
        @can("download" , $course)
            <span style="font-size: 13px;float: left">
                <a  href="{{ route('courses.downloadAllLinks' , $course->id) }}" class="detail-download" style="color: #5a97d9;">دریافت همه لینک های دانلود
                <i class="icon-download" style="float: left;margin-right: 10px !important;"></i>
                </a>
            </span>
        @endcan
    </div>


    <div class="episodes-list-section">
        @foreach($lessons as $index => $lesson)
            <div
                class="episodes-list-item  @cannot("download" , $lesson) lock @endcannot">  {{-- اگر دتسرسی دانلود نداره قفلش کن --}}
                <div class="section-right">
                    <span class="episodes-list-number">{{ $index + 1 }}</span>
                    <div class="episodes-list-title">
                        <a href="{{ $lesson->path() }}">{{ $lesson->title }}</a>
                    </div>
                </div>

                <div class="section-left">
                    <div class="episodes-list-details">
                        <div class="episodes-list-details">
                            <span class="detail-type">{{ $lesson->is_free ? "رایگان" : '' }}</span>
                            <span class="detail-time">{{ $lesson->time }}</span>
                            {{-- @can("download" , $lesson)   -> 1)name  2)parameters($user(default),$lesson دوره ها پاس دادیم) --}}
                            <a class="detail-download"
                               @can("download" ,$lesson) href="{{ $lesson->downloadLink() }}" @endcan>
                                <i class="icon-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
