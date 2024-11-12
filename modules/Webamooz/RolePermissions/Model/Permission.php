<?php

namespace Webamooz\RolePermissions\Model;

class Permission extends \Spatie\Permission\Models\Permission
{

    const PERMISSION_MANAGE_CATEGORIES = 'manage categories';
    const PERMISSION_MANAGE_COURSES = 'manage courses';
    const PERMISSION_MANAGE_USERS = 'manage users';
    const PERMISSION_MANAGE_OWN_COURSES = 'manage own courses';  //دوره های خودش مدیریت بکنه
    const PERMISSION_MANAGE_ROLE_PERMISSION = 'manage role_permissions';
    const PERMISSION_MANAGE_PAYMENTS = 'manage payments';
    const PERMISSION_MANAGE_SETTLEMENTS = 'manage settlements';
    const PERMISSION_MANAGE_DISCOUNT = 'manage discounts';
    const PERMISSION_MANAGE_TICKETS = 'manage tickets';
    const PERMISSION_MANAGE_COMMENTS = 'manage comments';
    const PERMISSION_MANAGE_SLIDES = 'manage slides';
    const PERMISSION_TEACH = 'teach';
    const PERMISSION_SUPER_ADMIN = 'super admin';

    public static array $permissions = [  //نمایش لیست بعد سیدر در ویو
        self::PERMISSION_SUPER_ADMIN,
        self::PERMISSION_TEACH,
        self::PERMISSION_MANAGE_CATEGORIES,
        self::PERMISSION_MANAGE_ROLE_PERMISSION,
        self::PERMISSION_MANAGE_COURSES,
        self::PERMISSION_MANAGE_OWN_COURSES,
        self::PERMISSION_MANAGE_USERS,
        self::PERMISSION_MANAGE_PAYMENTS,
        self::PERMISSION_MANAGE_SETTLEMENTS,
        self::PERMISSION_MANAGE_DISCOUNT,
        self::PERMISSION_MANAGE_TICKETS,
        self::PERMISSION_MANAGE_COMMENTS,
        self::PERMISSION_MANAGE_SLIDES,
    ];

}
