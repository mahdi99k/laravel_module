<x-mail::message>
# کد فعال سازی حساب {{ $firstName }} در وب آموز

کد از طرف سایت وب آموز برای شما ارسال شده. **در صورتی که در سایت ثبت نام نکرده اید** این ایمیل را نادیده بگیرید


<x-mail::panel>
کد فعال سازی شما : {{ $code }}
</x-mail::panel>

با تشکر از,<br>
{{ config('app.name') }}
</x-mail::message>