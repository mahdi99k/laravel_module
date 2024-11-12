<div class="transition-comment {{ $isAnswer ? 'is-answer' : '' }}">
    <div class="transition-comment-header">
       <span>
           <img src="{{ $comment->user->thumb }}" class="logo-pic" alt="{{ $comment->user->name }}">
       </span>
        <span class="nav-comment-status">
            <p class="username"> کاربر : {{ $comment->user->name }}</p>
            <p class="comment-date">{{ $comment->created_at->diffForHumans() }}</p>
            <span> وضعیت : <span class="confirmation_status {{ $comment->getStatusCssClass() }}">@lang($comment->status)</span></span>
        </span>

        @if ($isAnswer)
            <div class="comment-action">
                <a href="" class="item-delete mlg-15 btn_red_customize" title="حذف"
                   onclick="deleteItem(event , '{{ route('comments.destroy' , $comment->id) }}' , 'div.transition-comment-header')"></a>
                <a href="" class="item-confirm mlg-15 btn_success_customize" title="تایید شده" onclick="updateConfirmationStatus(event ,
                    '{{ route('comments.accept' , $comment->id) }}' , 'آیا از تایید این نظر اطمینان دارید؟' ,'تایید شده','confirmation_status' ,'div.transition-comment-header' , 'span.')"></a>
                <a href="" class="item-reject mlg-15 btn_red_customize" title="رد شده" onclick="updateConfirmationStatus(event,
                    '{{ route('comments.reject' , $comment->id) }}' , 'آیا از رد این نظر اطمینان دارد؟' ,'رد شده', 'confirmation_status' ,'div.transition-comment-header' , 'span.')"></a>
            </div>
        @endif

    </div>
    <div class="transition-comment-body">
        <pre>{{ $comment->body }}</pre>
    </div>
</div>
