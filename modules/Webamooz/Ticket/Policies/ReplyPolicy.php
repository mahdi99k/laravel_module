<?php

namespace Webamooz\Ticket\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\User\Models\User;

class ReplyPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

}
