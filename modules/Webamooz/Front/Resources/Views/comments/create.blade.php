<div class="comment-main">
    <div class="ct-header">
        <h3>نظرات ( 180 )</h3>
        <p>نظر خود را در مورد این مقاله مطرح کنید</p>
    </div>

    <form action="{{ route('comments.store') }}" method="POST">
        @csrf
        <input type="hidden" name="commentable_id" class="w-100" value="{{ $commentable->id }}">  {{-- get_class(object) --}}
        <input type="hidden" name="commentable_type" class="w-100" value="{{ get_class($commentable) }}">  {{-- get_class(object) --}}
        <div class="ct-row">
            <div class="ct-textarea">
                <x-textarea class="txt ct-textarea-field" name="body" placeholder="متن نظر را وارد نمایید" />
            </div>
        </div>
        <div class="ct-row">
            <div class="send-comment">
                <button type="submit" class="btn i-t">ثبت نظر</button>
            </div>
        </div>
    </form>

</div>
