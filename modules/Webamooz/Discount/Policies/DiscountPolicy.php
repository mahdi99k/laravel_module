<?php

namespace Webamooz\Discount\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\User\Models\User;

class DiscountPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    public function manage($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_DISCOUNT)) return true;  //hasAnyPermission->array
        return null;
    }

}
