@extends('Dashboard::master')

@section('title' , 'مشاهده نظر')
@section('breadcrumb')
    <li><a href="{{ route('comments.index') }}">نظرات</a></li>
@endsection

@section('content')
    <div class="main-content">
        <div class="show-comment">
            <div class="ct__header">
                <div class="comment-info">
                    <a class="back" href="{{ route('comments.index') }}"></a>
                    <div>
                        <p class="comment-name"><a href="">{{ $comment->commentable->title }}</a></p>
                    </div>
                </div>
            </div>

            @include('Comments::comment' , ['comment' => $comment , 'isAnswer' => false])

            @foreach ($comment->comments as $reply)
                @include('Comments::comment' , ['comment' => $reply , 'isAnswer' => true])
            @endforeach

        </div>
        @if ($comment->status == \Webamooz\Comment\Models\Comment::STATUS_APPROVED)
            <div class="answer-comment">
                <p class="p-answer-comment">ارسال پاسخ</p>
                <form action="{{ route('comments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                    <input type="hidden" name="commentable_id" class="w-100" value="{{ $comment->commentable->id }}">  {{-- get_class(object) --}}
                    <input type="hidden" name="commentable_type" class="w-100" value="{{ get_class($comment->commentable) }}">  {{-- get_class(object) --}}
                    <x-textarea class="txt hi-220px" name="body" placeholder="متن دیدگاه" />

                    <button type="submit" class="btn i-t">ثبت پاسخ</button>
                </form>
            </div>
        @else
            <p class="text-danger_custom">جهت ارسال پاسخ به این دیدیگاه لطفا ابتدا آن را تایید کنید.</p>
        @endif
    </div>
@endsection
