@extends('Dashboard::master')

@section('title' , 'ویرایش دوره')
@section('breadcrumb')
    <li><a href="{{ route('courses.index') }}" title="دوره ها">دوره ها</a></li>
    <li><a href="#" title="ویرایش دوره">ویرایش دوره</a></li>
@endsection


@section('content')
    <div class="row no-gutters bg-white">
        <div class="col-12">
            <p class="box__title">ویرایش دوره</p>

            <form action="{{ route('courses.update' , $course->id) }}" method="post" class="padding-30" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <x-input type="text" name="title" placeholder="عنوان دوره"  required value="{{ $course->title }}"/>
                <x-input type="text" name="slug" placeholder="نام انگلیسی دوره" class="text-left" required value="{{ $course->slug }}"/>

                <div class="d-flex multi-text">
                    <x-input type="text" name="priority" placeholder="الویت ردیف دوره" class="text-left" value="{{ $course->priority }}"/>
                    <x-input type="text" name="price" placeholder="مبلغ دوره" class="text-left" value="{{ $course->price }}" />
                    <x-input type="number" name="percent" placeholder="درصد مدرس" class="text-left" required value="{{ $course->percent }}" />
                </div>

                <x-select name="teacher_id" class="text" required>
                    <option value="">انتخاب مدرس دوره</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}"
                                @if ($teacher->id == $course->teacher_id) selected @endif>{{ $teacher->name }}</option>
                    @endforeach
                </x-select>

                <x-tag-select name="tags"/>

                <x-select name="type" required>
                    <option value="">نوع دوره</option>
                    @foreach(\Webamooz\Course\Models\Course::$types as $type)
                        <option value="{{ $type }}" @if($type == $course->type) selected @endif>@lang($type)</option>
                    @endforeach
                </x-select>

                <x-select name="status" required>
                    <option value="">وضعیت دوره</option>
                    @foreach (\Webamooz\Course\Models\Course::$statuses as $status)
                        <option value="{{ $status }}"
                                @if($status == $course->status) selected @endif>@lang($status)</option>
                    @endforeach
                </x-select>

                {{--<x-select name="confirmation_status" required>
                    <option value="">وضعیت تایید دوره</option>
                    @foreach (\Webamooz\Course\Models\Course::$confirmationStatuses as $confirmStatus)
                        <option value="{{ $confirmStatus }}"
                                @if($confirmStatus == $course->confirmation_status) selected @endif>@lang($confirmStatus)</option>
                    @endforeach
                </x-select>--}}

                <x-select name="category_id" required>
                    <option value="">دسته بندی</option>
                    @foreach ($categories as $key => $value)
                        <option value="{{ $key }}" @if($key == $course->category_id) selected @endif>{{ $value }}</option>
                    @endforeach
                </x-select>

                <x-file name="image" placeholder="آپلود بنر دوره" :value="$course->media" />  {{-- value="{{ $course->media->files[300] }}" --}}

                <x-text-area name="body" placeholder="توضیحات دوره" value="{{ $course->body }}" />

                <button type="submit" class="btn btn-webamooz_net mt-4">به روزرسانی</button>

            </form>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('panel/js/tagsInput.js') }}"></script>
@endsection
