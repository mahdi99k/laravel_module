<?php

namespace Webamooz\Payment\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\User\Models\User;

class PaymentPolicy
{
    use HandlesAuthorization;


    public function __construct()
    {
        //
    }

    public function manage($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_PAYMENTS)) return true;
        return null;
    }

}
