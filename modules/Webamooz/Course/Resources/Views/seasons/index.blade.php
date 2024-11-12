<div class="col-12 bg-white margin-bottom-15 border-radius-3">
    <p class="box__title">سرفصل ها</p>

    <form action="{{ route('seasons.store' , $course->id) }}" method="post" class="padding-30">
        @csrf
        <x-input type="text" name="title" placeholder="عنوان سرفصل" class="text" />
        <x-input type="text" name="number" placeholder="شماره سرفصل" class="text" />
        <button type="submit" class="btn btn-webamooz_net mt-5">اضافه کردن</button>
    </form>

    <div class="table__box padding-30">
        <table class="table">
            <thead role="rowgroup">
            <tr role="row" class="title-row">
                <th class="p-r-90">شماره فصل</th>
                <th>عنوان فصل</th>
                <th>وضعیت</th>
                <th>وضعیت تایید</th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            @foreach($course->seasons/*->where('user_id' , auth()->user()->id)*/ as $season)
                <tr role="row" class="">
                    <td><a href="">{{ $season->number }}</a></td>
                    <td><a href="">{{ $season->title }}</a></td>
                    <td class="status">@lang($season->status)</td>
                    <td class="confirmation_status">@lang($season->confirmation_status)</td>
                    <td>
                        <a href="" class="item-delete mlg-15 btn_red_customize" title="حذف"
                           onclick="deleteItem(event , '{{ route('seasons.destroy' , $season->id) }}')"></a>
                        <a href="{{ route('seasons.edit' , $season->id) }}" class="item-edit btn_info_customize mlg-15" title="ویرایش"></a>


                        {{-- role(teacher) -> فقط میتونه مدرس حذف یا ویرایش کنه دسترسی به تایید یا رد یا قفل یا باز ندارد --}}
                        @can(\Webamooz\RolePermissions\Model\Permission::PERMISSION_MANAGE_COURSES)

                            <a href="" onclick="updateConfirmationStatus(event , '{{ route('seasons.accept' , $season->id) }}' ,
                                'آیا از تایید سرفصل دوره مطمعن هستید؟' , 'تایید شده' , 'confirmation_status')"
                               class="item-confirm mlg-15 btn_success_customize" title="تایید شده"></a>

                            <a href="" onclick="updateConfirmationStatus(event , '{{ route('seasons.reject' , $season->id) }}' ,
                                'آیا از رد سرفصل دوره مطمعن هستید؟' , 'رد شده' , 'confirmation_status')"
                               class="item-reject mlg-15 btn_red_customize" title="رد شده"></a>

                            @if ($season->status == \Webamooz\Course\Models\Season::STATUS_OPENED)
                                <a href="" onclick="updateConfirmationStatus(event , '{{ route('seasons.lock' , $season->id) }}' ,
                                    'آیا از قفل شدن دوره مطمعن هستید؟' , 'قفل شده' , 'status')"
                                   class="item-lock mlg-15 btn_lock_customize" title="قفل شده"></a>
                            @else
                                <a href="" onclick="updateConfirmationStatus(event , '{{ route('seasons.unlock' , $season->id) }}' ,
                                    'آیا از باز کردن دوره مطمعن هستید؟' , 'باز شده' , 'status')"
                                   class="item-unlock mlg-15 btn_unlock_customize" title="باز شده"></a>
                            @endif
                        @endcan

                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
</div>
