<x-mail::message>
# یک کامنت جدید برای دوره {{ $comment->commentable->title }} ارسال شده است
مدرس گرامی یک کامنت جدید برای دوره **{{ $comment->commentable->title }}** در سایت وب آموز ارسال شده است. لطفا در اسرع وقت پاسخ مناسب ارسال فرمایید

<x-mail::panel>
جهت رفتن به دوره روی لینک زیر کلیک کنید.
@component('mail::button' , ['url' => $comment->commentable->path()])
مشاهده دوره
@endcomponent
</x-mail::panel>

با تشکر از,<br>
{{ config('app.name') }}
</x-mail::message>

