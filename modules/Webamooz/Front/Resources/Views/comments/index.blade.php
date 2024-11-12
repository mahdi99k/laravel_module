<div class="container">
    <div class="comments">
        <!-- Create From Comment -->
        @if (auth()->user())
            @include('Front::comments.create' , ['commentable' => $course])
        @else
            <div class="comment-main">
                <div class="ct-header">
                    <p>برای ثبت دیدگاه باید ابتدا <a href="{{ route('login') }}" class="text-info_custom">وارد سایت</a> شوید</p>
                </div>
            </div>
        @endif

        <div class="comments-list">
            {{-- Modal Reply --}}
            @auth
                @includeIf('Front::comments.reply',['commentable' => $course])
            @endauth

            <!-- SingleCourse.blade.php -> @includeIf('Front::comments.index' ,['commentable' => $course]) -->
            @foreach($commentable->approvedComments as $comment)
            <ul class="comment-list-ul">
                @auth
                    <div class="div-btn-answer">
                        <button class="btn-answer" onclick="setCommentId({{ $comment->id }})">پاسخ به دیدگاه</button>
                    </div>
                @endauth

                <li class="is-comment">
                    <div class="comment-header">
                        <div class="comment-header-avatar">
                            <img src="{{ $comment->user->thumb }}" alt="{{ $comment->user->name }}">
                        </div>
                        <div class="comment-header-detail">
                            <div class="comment-header-name"> کاربر : {{ $comment->user->name }}</div>
                            <div
                                class="comment-header-date">{{ \Morilog\Jalali\Jalalian::fromCarbon($comment->created_at) }}</div>
                        </div>
                    </div>
                    <div class="comment-content">
                        <p>{{ $comment->body }}</p>
                    </div>
                </li>

                @foreach ($comment->comments->where('status' , 'approved') as $reply)
                    <li class="is-answer">
                        <div class="comment-header">
                            <div class="comment-header-avatar">
                                <img src="{{ $reply->user->thumb }}" alt="{{ $reply->user->name }}">
                            </div>
                            <div class="comment-header-detail">
                                <div class="comment-header-name"> کاربر : {{ $reply->user->name }}</div>
                                <div
                                    class="comment-header-date">{{ \Morilog\Jalali\Jalalian::fromCarbon($reply->created_at) }}</div>
                            </div>
                        </div>
                        <div class="comment-content">
                            <p>{{ $reply->body }}</p>
                        </div>
                    </li>
                @endforeach

            </ul>
            @endforeach

        </div>
    </div>
</div>

