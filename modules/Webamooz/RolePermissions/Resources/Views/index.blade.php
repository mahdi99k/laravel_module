@extends('Dashboard::master')

@section('title' , 'لیست نقش کاربران')
@section('breadcrumb')
    <li><a href="#" title="نقش های کاربر">نقش های کاربر</a></li>
@endsection

@section('content')

<div class="row no-gutters">
    <div class="col-8 margin-left-10 margin-bottom-15 border-radius-3">
        <p class="box__title">نقش کاربران</p>
        <div class="table__box">
            <table class="table">
                <thead role="rowgroup">
                <tr role="row" class="title-row">
                    <th>شناسه</th>
                    <th>نقش کاربر</th>
                    <th>مجوز ها</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($roles as $role)
                    <tr role="row" class="">
                        <td><a href="">{{ $role->id }}</a></td>
                        <td><a href="">@lang($role->name)</a></td>
                        <td>
                            <ul>
                                @foreach ($role->permissions as $permission)
                                    <li>@lang($permission->name)</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <a href="" class="item-delete mlg-15 btn_red_customize" title="حذف"
                            onclick="deleteItem(event, '{{ route('role-permissions.destroy' ,$role->id) }}')"></a>
                            <a href="{{ route('role-permissions.edit' , $role->id) }}" class="item-edit btn_info_customize" title="ویرایش"></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-4 bg-white">
        @includeIf('RolePermissions::create')
    </div>
</div>

@endsection
