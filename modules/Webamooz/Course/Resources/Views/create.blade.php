@extends('Dashboard::master')

@section('title' , 'ایجاد دوره')
@section('breadcrumb')
    <li><a href="{{ route('courses.index') }}" title="دوره ها">دوره ها</a></li>
    <li><a href="#" title="ایجاد دوره">ایجاد دوره</a></li>
@endsection


@section('content')
    <div class="row no-gutters bg-white">
        <div class="col-12">
            <p class="box__title">ایجاد دوره جدید</p>

            <form action="{{ route('courses.store') }}" method="post" class="padding-30" enctype="multipart/form-data">
                @csrf

                <x-input type="text" name="title" placeholder="عنوان دوره" required/>
                <x-input type="text" name="slug" placeholder="نام انگلیسی دوره" class="text-left" required/>

                <div class="d-flex multi-text">
                    <x-input type="text" name="priority" placeholder="الویت ردیف دوره" class="text-left"/>
                    <x-input type="text" name="price" placeholder="مبلغ دوره" class="text-left" />
                    <x-input type="number" name="percent" placeholder="درصد مدرس" class="text-left" required/>
                </div>

                <x-select name="teacher_id" class="text" required>
                    <option value="">انتخاب مدرس دوره</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}"
                                @if ($teacher->id == old('teacher_id')) selected @endif>{{ $teacher->name }}</option>
                    @endforeach
                </x-select>

                <x-tag-select name="tags"/>

                <x-select name="type" required>
                    <option value="">نوع دوره</option>
                    @foreach(\Webamooz\Course\Models\Course::$types as $type)
                        <option value="{{ $type }}" @if($type == old('type')) selected @endif>@lang($type)</option>
                    @endforeach
                </x-select>

                <x-select name="status" required>
                    <option value="">وضعیت دوره</option>
                    @foreach (\Webamooz\Course\Models\Course::$statuses as $status)
                        <option value="{{ $status }}"
                                @if($status == old('status')) selected @endif>@lang($status)</option>
                    @endforeach
                </x-select>


                {{--<x-select name="confirmation_status" required>
                    <option value="">وضعیت تایید دوره</option>
                    @foreach (\Webamooz\Course\Models\Course::$confirmationStatuses as $confirmStatus)
                        <option value="{{ $confirmStatus }}"
                                @if($confirmStatus == old('confirmation_status')) selected @endif>@lang($confirmStatus)</option>
                    @endforeach
                </x-select>--}}

                <x-select name="category_id" required>
                    <option value="">دسته بندی</option>
                    @foreach ($categories as $key => $value)
                        <option value="{{ $key }}" @if($key == old('category_id')) selected @endif>{{ $value }}</option>
                    @endforeach
                </x-select>

                <x-file name="image" placeholder="آپلود بنر دوره" required/>

                <x-text-area name="body" placeholder="توضیحات دوره"/>

                <button type="submit" class="btn btn-webamooz_net mt-4">ایجاد</button>

            </form>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('panel/js/tagsInput.js') }}"></script>
@endsection
