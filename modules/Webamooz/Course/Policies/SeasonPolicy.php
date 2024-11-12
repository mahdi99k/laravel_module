<?php

namespace Webamooz\Course\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\User\Models\User;

class SeasonPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    public function manage(User $user)  //درون پالیسی با نوشن مادل کاربر دسترسی داریم به کاربر لاگین شده
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES);
    }

    public function create(User $user)  //یا مدیریت دسته بندی یا مدیریت دسته های من
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES) ||
            $user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
    }

    public function edit($user, $season)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;

        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES) && $season->course->teacher_id == $user->id;
    }

    public function delete($user, $season)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;  //فقط مدیر دوره ها بتونه حذف بکنه نه (مدرس دوره یا صاحب دوره)
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES) && $season->course->teacher_id == $user->id) return true;
        return null;
    }

    public function change_confirmation_status($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;
        return null;
    }

}
