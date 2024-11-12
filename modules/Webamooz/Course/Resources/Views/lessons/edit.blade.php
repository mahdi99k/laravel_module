@extends('Dashboard::master')

@section('title' , 'ویرایش درس')
@section('breadcrumb')
    <li><a href="{{ route('courses.index') }}" title="درس ها">درس ها</a></li>
    <li><a href="{{ route('courses.details' , $course->id) }}" title="{{ $course->title }}">{{ $course->title }}</a></li>
    <li><a href="#" title="ویرایش درس">ویرایش درس</a></li>
@endsection


@section('content')
    <div class="row no-gutters bg-white">
        <div class="col-12">
            <p class="box__title">ویرایش درس جدید</p>

            <form action="{{ route('lessons.update' , [$course->id , $lesson->id]) }}" method="post" class="padding-30" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <x-input type="text" name="title" placeholder="عنوان درس *" value="{{ $lesson->title }}" required/>
                <x-input type="text" name="slug" placeholder="نام انگلیسی درس اختیاری" class="text-left" value="{{ $lesson->slug }}" />
                <x-input type="number" name="time" placeholder="مدت زمان جلسه *" class="text-left" value="{{ $lesson->time }}" required/>
                <x-input type="number" name="number" placeholder="شماره جلسه" class="text-left" value="{{ $lesson->number }}" />

                @if ($seasons)
                    <x-select name="season_id" class="text" required>
                        <option value="">انتخاب سر فصل *</option>
                        @foreach ($seasons as $season)
                            <option value="{{ $season->id }}"
                                    @if ($season->id == $lesson->season_id) selected @endif>{{ $season->title }}</option>
                        @endforeach
                    </x-select>
                @endif

                <div class="w-50 mt-5">
                    <p class="box__title">ایا این درس رایگان است ؟ * </p>
                    <div class="notificationGroup">
                        <input id="lesson-upload-field-1" name="is_free" value="0" type="radio" @if(! $lesson->is_free) checked @endif> {{-- اگر رایگان نیست تیک بخوره --}}
                        <label for="lesson-upload-field-1">خیر</label>
                    </div>
                    <div class="notificationGroup">
                        <input id="lesson-upload-field-2" name="is_free" value="1" type="radio" @if($lesson->is_free) checked @endif> {{-- اگر رایگان تیک بخوره --}}
                        <label for="lesson-upload-field-2">بله</label>
                    </div>
                </div>

                <x-file name="lesson_file" placeholder="آپلود درس *" :value="$lesson->media" /> {{-- value="{{ $lesson->media->files[300] }}--}}

                <x-text-area name="body" placeholder="توضیحات درس" value="{{ $lesson->body }}"/>

                <button type="submit" class="btn btn-webamooz_net mt-4">به روزرسانی</button>

            </form>
        </div>
    </div>

@endsection
