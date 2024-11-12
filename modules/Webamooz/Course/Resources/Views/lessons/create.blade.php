@extends('Dashboard::master')

@section('title' , 'ایجاد درس')
@section('breadcrumb')
    <li><a href="{{ route('courses.index') }}" title="درس ها">درس ها</a></li>
    <li><a href="{{ route('courses.details' , $course->id) }}" title="{{ $course->title }}">{{ $course->title }}</a></li>
    <li><a href="#" title="ایجاد درس">ایجاد درس</a></li>
@endsection


@section('content')
    <div class="row no-gutters bg-white">
        <div class="col-12">
            <p class="box__title">ایجاد درس جدید</p>

            <form action="{{ route('lessons.store' , $course->id) }}" method="post" class="padding-30" enctype="multipart/form-data">
                @csrf

                <x-input type="text" name="title" placeholder="عنوان درس *" required/>
                <x-input type="text" name="slug" placeholder="نام انگلیسی درس اختیاری" class="text-left" />
                <x-input type="number" name="time" placeholder="مدت زمان جلسه *" class="text-left" required/>
                <x-input type="number" name="number" placeholder="شماره جلسه" class="text-left" />

                @if ($seasons)
                    <x-select name="season_id" class="text" required>
                        <option value="">انتخاب سر فصل *</option>
                        @foreach ($seasons as $season)
                            <option value="{{ $season->id }}"
                                    @if ($season->id == old('season_id')) selected @endif>{{ $season->title }}</option>
                        @endforeach
                    </x-select>
                @endif

                <div class="w-50 mt-5">
                    <p class="box__title">ایا این درس رایگان است ؟ * </p>
                    <div class="notificationGroup">
                        <input id="lesson-upload-field-1" name="is_free" value="0" type="radio" checked="">
                        <label for="lesson-upload-field-1">خیر</label>
                    </div>
                    <div class="notificationGroup">
                        <input id="lesson-upload-field-2" name="is_free" value="1" type="radio">
                        <label for="lesson-upload-field-2">بله</label>
                    </div>
                </div>

                <x-file name="lesson_file" placeholder="آپلود درس *" required/>

                <x-text-area name="body" placeholder="توضیحات درس"/>

                <button type="submit" class="btn btn-webamooz_net mt-4">ایجاد</button>

            </form>
        </div>
    </div>

@endsection
