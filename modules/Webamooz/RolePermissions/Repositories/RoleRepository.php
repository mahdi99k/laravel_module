<?php

namespace Webamooz\RolePermissions\Repositories;

use Spatie\Permission\Models\Role;

class RoleRepository
{

    public function all()
    {
        return Role::all();
    }

    public function store($request)
    {
        return Role::create([
            'name' => $request->name,
        ])->syncPermissions($request->permissions);
    }

    public function getById($role_id)
    {
        return Role::findOrFail($role_id);
    }

    public function update($role_id, $request)
    {
        $role = $this->getById($role_id);
        $role->update([
            'name' => $request->name
        ]);
        return $role->syncPermissions($request->permissions);
    }

    public function destroy($role_id)
    {
        return Role::where('id', $role_id)->delete();
    }

}
