<div class="w-100 mlg-15">
{{-- $attributes->merge(['class' => 'text']) هر مقداری تعریف کنیم مثل فوکوش یا اجباری میاد درون اتریبیوت + مرج کردیم کلاس پیش فرض تکست برای همه و هر چی بدیم میاد اضافه میکنه --}}
    <input type="{{ $type }}" name="{{ $name }}" placeholder="{{ $placeholder }}" {{ $attributes->merge(['class' => 'text w-100']) }} value="{{ old($name) }}" >
    <x-validation-error field='{{ str_replace("]" , "" ,str_replace("[" , "." , $name)) }}' />  {{-- برای اینکه در حالت اسم آرایه اینپوت بتونه خطا ها نمایش بده --}}
    {{-- from[name]=from.name -> str_replace("]" , '' ,str_replace("[" , "." , $name)) براکت بسته حذف کن در یک ریپلیس دیگه که براکت باز جایگزین نقطه کن در اسم اینپوت --}}
</div>

