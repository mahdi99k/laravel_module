<?php

namespace Webamooz\Slider\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Webamooz\RolePermissions\Model\Permission;

class SlidePolicy
{
    use HandlesAuthorization;

    public function __construct()
    {

    }

    public function manage($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_SLIDES)) return true;
        return null;
    }

}
