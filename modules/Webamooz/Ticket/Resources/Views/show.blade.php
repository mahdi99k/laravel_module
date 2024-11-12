@extends('Dashboard::master')

@section('title' , 'مشاهده تیکت')
@section('breadcrumb')
    <li><a href="{{ route('tickets.index') }}">تیکت ها</a></li>
    <li><a href="{{ route('tickets.show' , $ticket->id) }}" class="is-active">مشاهده تیکت</a></li>
@endsection

@section('content')
    <div class="main-content">
        <div class="show-comment">
            <div class="ct__header">
                <div class="comment-info">
                    <a class="back" href="{{ route('tickets.index') }}"></a>
                    <div>
                        <p class="comment-name"><a href="">{{ $ticket->title }}</a></p>
                    </div>
                </div>
            </div>


            @foreach($ticket->replies as $reply)
                {{-- is-answer -> اگر آیدی کسی که تیکت زده نخالف آیدی پاسخ تیکت بود اون یک جواب --}}
                <div class="transition-comment {{ $reply->user_id == $ticket->user_id ? "" : "is-answer" }}">
                    <div class="transition-comment-header">
                        <span><img src="{{ $reply->user->thumb }}" class="logo-pic"></span>
                        <span class="nav-comment-status">
                        <p class="username"> کاربر : {{ $reply->user->name }}</p>
                        <p class="comment-date">{{ $reply->created_at }}</p></span>
                        <div></div>
                    </div>
                    <div class="transition-comment-body">
                        <pre>{!! $reply->body !!}

                            @if ($reply->media_id)
                                <a href="{{ $reply->attachmentLink() }}" class="text-info_custom fw-bold m-0 p-0">دانلود فایل پیوست</a>
                            @endif
                        </pre>

                    </div>
                </div>
            @endforeach


        </div>
        <div class="answer-comment">
            <p class="p-answer-comment">ارسال پاسخ</p>
            <form action="{{ route('tickets.reply' , $ticket->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <x-textarea class="textarea" placeholder="متن پاسخ به این تیکت" name="body" required />
                <x-file placeholder="آپلود فایل پیوست" name="attachment"/>
                <button type="submit" class="btn btn-webamooz_net">ارسال پاسخ</button>
            </form>
        </div>
    </div>
    </div>
@endsection
