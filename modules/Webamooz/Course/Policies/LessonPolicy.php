<?php

namespace Webamooz\Course\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Webamooz\RolePermissions\Model\Permission;

class LessonPolicy
{
    use HandlesAuthorization;

    public function store($user, $course)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;
    }

    public function edit($user, $lesson)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES) ||
            ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES) && $user->id == $lesson->course->teacher_id)

        ) {
            return true;
        }
    }

    public function delete($user, $lesson)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES) ||
            ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES) && $user->id == $lesson->course->teacher_id)

        ) {
            return true;
        }
    }

    public function manage($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;
    }

    public function download($user, $lesson)
    {
        //یا مدیر سایت یا کسی که مدرس این دوره یا دانشجوی ثبت نامی بتونه دسترسی به دوره داشته باشد
        //contains -> اگر کاربر وجود داشت میاد از حالت آبجکت ی مقدار رشته ای میکشه بیرون
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES) || $user->id == $lesson->course->teacher_id ||
            $lesson->course->hasStudent($user->id) || $lesson->is_free) {
            return true;
        }
        return false;
    }

}
