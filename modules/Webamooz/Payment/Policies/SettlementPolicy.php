<?php

namespace Webamooz\Payment\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\User\Models\User;

class SettlementPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    public function index($user)
    {
        if ($user->hasAnyPermission([Permission::PERMISSION_MANAGE_SETTLEMENTS, Permission::PERMISSION_TEACH])) return true;
        return null;
    }

    public function manage($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_SETTLEMENTS)) return true;
        return null;
    }

    public function store($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_TEACH)) return true;
        return null;
    }

}












