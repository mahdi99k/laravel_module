<form action="{{ route('users.photo') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="profile__info border cursor-pointer text-center">
            <div class="avatar__img">
                <img src="{{ auth()->user()->thumb }}" class="avatar___img">
                <input type="file" name="userPhoto" accept="image/*" class="hidden avatar-img__input"
                onchange="this.form.submit()">
                <div class="v-dialog__container" style="display: block;"></div>
                <div class="box__camera default__avatar"></div>
            </div>

        <span class="profile__name"> کاربر : {{ auth()->user()->name }} </span>
    </div>
</form>
