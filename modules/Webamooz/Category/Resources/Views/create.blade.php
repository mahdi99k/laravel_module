<p class="box__title">ایجاد دسته بندی جدید</p>

{{--@if (session()->has('message'))
    <p class="text-success alert alert-success">{{ session()->get('message') }}</p>
@endif--}}

<form action="{{ route('categories.store') }}" method="post" class="padding-30">
    @csrf
    <input type="text" name="title" placeholder="نام دسته بندی" class="text" required>
    @error('title')
        <p class="text-danger_custom">{{ $message }}</p>
    @enderror
    <input type="text" name="slug" placeholder="نام انگلیسی دسته بندی" class="text" required>
    @error('slug')
    <p class="text-danger_custom">{{ $message }}</p>
    @enderror
    <p class="box__title margin-bottom-15">انتخاب دسته پدر</p>
        <select name="parent_id" id="parent_id">
                <option value="">ندارد</option>  {{-- value="" -> Null --}}
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->title }}</option>
            @endforeach
        </select>
    <button type="submit" class="btn btn-webamooz_net">اضافه کردن</button>
</form>
