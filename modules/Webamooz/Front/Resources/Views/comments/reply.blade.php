<div id="Modal2" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <p>ارسال پاسخ</p>
            <div class="close">&times;</div>
        </div>
        <div class="modal-body">
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" id="comment_id" name="comment_id" value="">
                <input type="hidden" name="commentable_id" class="w-100" value="{{ $commentable->id }}">  {{-- get_class(object) --}}
                <input type="hidden" name="commentable_type" class="w-100" value="{{ get_class($commentable) }}">  {{-- get_class(object) --}}
                <x-textarea class="txt hi-220px" name="body" placeholder="متن دیدگاه" />

                <button type="submit" class="btn i-t">ثبت پاسخ</button>
            </form>
        </div>

    </div>
</div>
