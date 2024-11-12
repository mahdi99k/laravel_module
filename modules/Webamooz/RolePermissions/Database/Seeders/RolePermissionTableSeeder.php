<?php

namespace Webamooz\RolePermissions\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionTableSeeder extends Seeder
{

    public function run()
    {
        //findOrCreate -> بیا پیدا کن اگر وجود داشت کاری نکن ولی اگر وجود نداشت بیا بساز + یک دیتا میگیره به صورت استرینگ و اسم دیتا
        foreach (\Webamooz\RolePermissions\Model\Permission::$permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        foreach (\Webamooz\RolePermissions\Model\Role::$roles as $roleName => $permission) {
            Role::findOrCreate($roleName)->givePermissionTo($permission);  //$permission -> array
        }
    }

}
