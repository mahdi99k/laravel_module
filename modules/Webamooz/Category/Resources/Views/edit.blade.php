@extends('Dashboard::master')

@section('title' , 'ویرایش دسته بندی')
@section('breadcrumb')
    <li><a href="{{ route('categories.index') }}" title="دسته بندی ها">دسته بندی ها</a></li>
    <li><a href="#" title="ویرایش دسته بندی">ویرایش دسته بندی</a></li>
@endsection


@section('content')
    <div class="row no-gutters">
        <div class="col-4 bg-white">
            <p class="box__title">به روزرسانی دسته بندی</p>

            <form action="{{ route('categories.update' , $category->id) }}" method="post" class="padding-30">
                @csrf
                @method('PATCH')

                <input type="text" name="title" placeholder="نام دسته بندی" class="text" value="{{ $category->title }}" required>
                @error('title')
                <p class="text-danger_custom">{{ $message }}</p>
                @enderror
                <input type="text" name="slug" placeholder="نام انگلیسی دسته بندی" class="text" value="{{ $category->slug }}" required>
                @error('slug')
                <p class="text-danger_custom">{{ $message }}</p>
                @enderror
                <p class="box__title margin-bottom-15">انتخاب دسته پدر</p>
                <select name="parent_id" id="parent_id">
                    <option value="">ندارد</option>  {{-- value="" -> Null --}}
                    @foreach ($categories as $categoryItem)
                        <option value="{{ $categoryItem->id }}"
                                @if($categoryItem->id == $category->parent_id) selected @endif>{{ $categoryItem->title }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-webamooz_net">به روزرسانی</button>
            </form>
        </div>
    </div>

@endsection
