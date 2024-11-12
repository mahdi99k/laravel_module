@extends('Dashboard::master')

@section('title' , 'ویرایش اسلاید')
@section('breadcrumb')
    <li><a href="{{ route('slides.index') }}" title="اسلاید ها">اسلاید ها</a></li>
    <li><a href="#" title="ویرایش اسلاید">ویرایش اسلاید</a></li>
@endsection


@section('content')
    <div class="row no-gutters bg-white">
        <div class="col-12">
            <p class="box__title">ویرایش اسلاید</p>

            <form action="{{ route('slides.update' , $slide->id) }}" method="post" class="padding-30" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <img src="{{ $slide->media->thumb }}" alt="" width="80">
                <x-input type="file" name="image" placeholder="تصویر" class="text" />
                <x-input type="text" name="priority" placeholder="الویت بندی" class="text" value="{{ $slide->priority }}" />
                <x-input type="text" name="link" placeholder="لینک" class="text" value="{{ $slide->link }}" />
                <p class="box__title margin-bottom-15 mt-5">وضعیت نمایش</p>
                <x-select name="status">
                    <option value="1" @if($slide->status == 1) selected @endif>فعال</option>
                    <option value="0" @if($slide->status == 0) selected @endif>غیر فعال</option>
                </x-select>

                <button type="submit" class="btn btn-webamooz_net mt-4">به روزرسانی</button>

            </form>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('panel/js/tagsInput.js') }}"></script>
@endsection
