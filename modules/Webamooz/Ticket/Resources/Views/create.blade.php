@extends('Dashboard::master')

@section('title' , 'ایجاد تیکت')
@section('breadcrumb')
    <li><a href="{{ route('tickets.index') }}">تیکت ها</a></li>
    <li><a href="{{ route('tickets.create') }}" class="is-active">ارسال تیکت جدید</a></li>
@endsection

@section('content')
    <div class="main-content padding-0">
        <p class="box__title">ایجاد تیکت جدید</p>
        <div class="row no-gutters bg-white">
            <div class="col-12">
                <form action="{{ route('tickets.store') }}" class="padding-30" method="POST" enctype="multipart/form-data">
                    @csrf
                    <x-input type="text" class="text" placeholder="عنوان تیکت" name="title" required />

                    <span style="margin-top: 10px !important;margin-bottom: 3px!important;">انتخاب دوره</span>
                    <x-select name="course" class="m-0">
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </x-select>

                    <x-textarea placeholder="متن تیکت" class="text" name="body" />

                    <x-file type="file" placeholder="آپلود فایل پیوست" name="attachment" />
                    <button class="btn btn-webamooz_net">ایجاد تیکت</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('panel/js/tagsInput.js') }}"></script>
@endsection
