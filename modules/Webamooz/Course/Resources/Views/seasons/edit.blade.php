@extends('Dashboard::master')

@section('title' , 'ویرایش سر فصل')
@section('breadcrumb')
    <li><a href="{{ route('courses.details' , $season->course_id) }}" title="{{ $season->course->title }}">{{ $season->course->title }}</a></li>
    <li><a href="#" title="ویرایش سر فصل">ویرایش سر فصل</a></li>
@endsection

@section('content')
    <div class="row no-gutters bg-white">
        <div class="col-12">
            <p class="box__title">ویرایش سر فصل</p>

            <form action="{{ route('seasons.update' , $season->id) }}" method="post" class="padding-30" >
                @csrf
                @method('PATCH')

                <x-input type="text" name="title" placeholder="عنوان سرفصل" class="text" value="{{ $season->title }}" />
                <x-input type="text" name="number" placeholder="شماره سرفصل" class="text" value="{{ $season->number }}" />
                <button type="submit" class="btn btn-webamooz_net mt-4">به روزرسانی</button>

            </form>
        </div>
    </div>

@endsection

