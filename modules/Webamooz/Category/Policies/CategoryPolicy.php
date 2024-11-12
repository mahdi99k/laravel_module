<?php

namespace Webamooz\Category\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\User\Models\User;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function manage(User $user)  //درون پالیسی با نوشن مادل کاربر دسترسی داریم به کاربر لاگین شده
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_CATEGORIES);
    }

}
