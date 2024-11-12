@extends('Dashboard::master')

@section('title' , 'لیست دسته بندی ها')
@section('breadcrumb')
    <li><a href="#" title="دسته بندی ها">دسته بندی ها</a></li>
@endsection

@section('content')

    <div class="row no-gutters">
        <div class="col-8 margin-left-10 margin-bottom-15 border-radius-3">
            <p class="box__title">دسته بندی ها</p>
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                    <tr role="row" class="title-row">
                        <th>شناسه</th>
                        <th>نام دسته بندی</th>
                        <th>نام انگلیسی دسته بندی</th>
                        <th>دسته پدر</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($categories as $category)
                        <tr role="row" class="">
                            <td><a href="">{{ $category->id }}</a></td>
                            <td><a href="">{{ $category->title }}</a></td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->parent }}</td> {{-- use Model -> getParentAttribute --}}
                            <td>
                                <a href="" class="item-delete mlg-15 btn_red_customize" title="حذف"
                                   onclick="deleteItem(event, '{{ route('categories.destroy' ,$category->id) }}')"></a>
                                <a href="" target="_blank" class="item-eye mlg-15 btn_warning_customize"
                                   title="مشاهده"></a>
                                <a href="{{ route('categories.edit' , $category->id) }}"
                                   class="item-edit btn_info_customize" title="ویرایش"></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-4 bg-white">
            @includeIf('Category::create')
        </div>
    </div>

@endsection
