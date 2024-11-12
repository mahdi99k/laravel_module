<?php

namespace Webamooz\RolePermissions\Model;

class Role extends \Spatie\Permission\Models\Role
{

    const ROLE_TEACHER = 'teacher';
    const ROLE_SUPER_ADMIN = 'super admin';
    const ROLE_STUDENT = 'student';
    public static array $roles = [
        self::ROLE_TEACHER => [
            Permission::PERMISSION_TEACH,  //has many-permission inside role
        ],
        self::ROLE_SUPER_ADMIN => [
            Permission::PERMISSION_SUPER_ADMIN,
        ],
        self::ROLE_STUDENT => [
        ]
    ];

}
