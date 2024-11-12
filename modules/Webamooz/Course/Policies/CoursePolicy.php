<?php

namespace Webamooz\Course\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Webamooz\Course\Repositories\CourseRepository;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\User\Models\User;

class CoursePolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    public function manage(User $user)  //درون پالیسی با نوشن مادل کاربر دسترسی داریم به کاربر لاگین شده
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;
        return null;
    }

    public function index($user)
    {
        if ($user->hasAnyPermission([Permission::PERMISSION_MANAGE_COURSES, Permission::PERMISSION_MANAGE_OWN_COURSES])) return true;
        return null;
    }

    public function create(User $user)  //یا مدیریت دسته بندی یا مدیریت دسته های من
    {
        if ($user->hasAnyPermission([Permission::PERMISSION_MANAGE_COURSES, Permission::PERMISSION_MANAGE_OWN_COURSES])) return true;
        return null;
    }

    public function store(User $user)  //یا مدیریت دسته بندی یا مدیریت دسته های من
    {
        if ($user->hasAnyPermission([Permission::PERMISSION_MANAGE_COURSES, Permission::PERMISSION_MANAGE_OWN_COURSES])) return true;
        return null;
    }

    public function edit($user, $course)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;

        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES) && $course->teacher_id == $user->id) return true;

        return null;
    }

    public function delete($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;  //فقط مدیر دوره ها بتونه حذف بکنه نه (مدرس دوره یا صاحب دوره)
        return null;
    }

    public function change_confirmation_status($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;
        return null;
    }

    public function details($user, $course)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES) && $course->teacher_id == $user->id) return true;
        return null;
    }

    public function createSeason($user, $course)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES) && $course->teacher_id == $user->id) return true;
        return null;
    }

    public function createLesson($user, $course)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES) ||
            ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES) && $course->teacher_id == $user->id)
        ) return true;;
    }

    public function download($user, $course)
    {
        //یا مدیر سایت یا کسی که مدرس این دوره یا دانشجوی ثبت نامی بتونه دسترسی به دوره داشته باشد
        //contains -> اگر کاربر وجود داشت میاد از حالت آبجکت ی مقدار رشته ای میکشه بیرون
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES) ||
            $user->id == $course->teacher_id || $course->hasStudent($user->id)) {
            return true;
        }
        return false;
    }

}
