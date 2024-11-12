@extends('Dashboard::master')

@section('title' , 'ویرایش نقش کاربری')
@section('breadcrumb')
    <li><a href="{{ route('role-permissions.index') }}" title="نقش کاربری">نقش کاربری</a></li>
    <li><a href="#" title="ویرایش نقش کاربر">ویرایش نقش کاربر</a></li>
@endsection


@section('content')
    <div class="row no-gutters">
        <div class="col-4 bg-white">
            <p class="box__title">ویرایش نقش کاربری</p>

            <form action="{{ route('role-permissions.update', $role->id) }}" method="post" class="padding-30">
                @csrf
                @method('PATCH')

                <input type="text" name="name" placeholder="نام نقش" class="text" value="{{ $role->name }}" required>
                @error('name')
                <p class="text-danger_custom">{{ $message }}</p>
                @enderror
                <p class="box__title margin-bottom-15">انتخاب مجوز ها</p>
                @foreach($permissions as $permission)
                    <label class="ui-checkbox pt-4">
                        {{-- key(inputName) => value(inputValue) --}}
                        <input type="checkbox" name="permissions[{{ $permission->name }}]" data-id="{{ $permission->id }}"
                               value="{{ $permission->name }}" @if($role->hasPermissionTo($permission->name)) checked @endif>
                        <span class="checkmark"></span>
                        @lang($permission->name)  {{--  @lang($permission->name) === {{ __($permission->name) }}  --}}
                    </label>
                @endforeach
                @error('permissions')
                <p class="text-danger_custom">{{ $message }}</p>
                @enderror

                <button type="submit" class="btn btn-webamooz_net mt-10">به روزرسانی</button>
            </form>

        </div>
    </div>
@endsection
