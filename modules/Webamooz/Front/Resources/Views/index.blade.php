@extends('Front::layouts.master')

@section('title' , 'وب آموز | آموزش برنامه‌ نویسی و طراحی وب')

@section('content')

    <main id="index">
        <article class="container article">
            @includeIf('Front::layouts.header-ads')

            @include('Front::layouts.top-info')  {{-- sliders center + 2 sliders inside --}}

            @include('Front::layouts.newestCourses')

            @includeIf('Front::layouts.popularCourses')
        </article>
        @includeIf('Front::layouts.latestArticles')
    </main>

@endsection
