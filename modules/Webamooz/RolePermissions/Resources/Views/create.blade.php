<p class="box__title">ایجاد نقش کاربری جدید</p>

<form action="{{ route('role-permissions.store') }}" method="post" class="padding-30">
    @csrf
    <input type="text" name="name" placeholder="نام نقش" class="text" value="{{ old('name') }}" required>
    @error('name')
    <p class="text-danger_custom">{{ $message }}</p>
    @enderror
    <p class="box__title margin-bottom-15">انتخاب مجوز ها</p>
    @foreach($permissions as $permission)
        <label class="ui-checkbox pt-4">
            {{-- key(inputName) => value(inputValue) --}}
            <input type="checkbox" name="permissions[{{ $permission->name }}]" data-id="{{ $permission->id }}" value="{{ $permission->name }}"
                   @if(is_array(old('permissions')) && array_key_exists($permission->name , old('permissions'))) checked @endif>
            <span class="checkmark"></span>
            @lang($permission->name)  {{--  @lang($permission->name) === {{ __($permission->name) }}  --}}
        </label>
    @endforeach
    @error('permissions')
    <p class="text-danger_custom">{{ $message }}</p>
    @enderror

    <button type="submit" class="btn btn-webamooz_net mt-10">اضافه کردن</button>
</form>
