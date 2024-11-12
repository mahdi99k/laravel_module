<?php

namespace Webamooz\Ticket\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\User\Models\User;

class TicketPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    public function show($user, $ticket)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_TICKETS)) return true;
        if ($ticket->user_id == $user->id) return true;
        return null;
    }

    public function delete($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_TICKETS)) return true;
        return null;
    }

}
