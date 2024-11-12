<p class="box__title">ایجاد اسلاید جدید</p>

@if (session()->has('message'))
    <p class="text-success alert alert-success">{{ session()->get('message') }}</p>
@endif

<form action="{{ route('slides.store') }}" method="POST" class="padding-30" enctype="multipart/form-data">
    @csrf
    <x-input type="file" name="image" placeholder="تصویر" class="text" required />
    <x-input type="text" name="priority" placeholder="الویت بندی" class="text" />
    <x-input type="text" name="link" placeholder="لینک" class="text" />
    <p class="box__title margin-bottom-15 mt-5">وضعیت نمایش</p>
    <x-select name="status">
        <option value="1" selected>فعال</option>
        <option value="0">غیر فعال</option>
    </x-select>

    <button type="submit" class="btn btn-webamooz_net mt-5">اضافه کردن</button>
</form>
