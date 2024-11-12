<?php

namespace Webamooz\RolePermissions\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use Webamooz\RolePermissions\Database\Seeders\RolePermissionTableSeeder;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\RolePermissions\Model\Role;
use Webamooz\User\Models\User;

class  RolesTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    public function test_permitted_user_can_see_index()
    {
        $this->actingAsAdminRole();
        $this->get(route('role-permissions.index'))->assertOk();
    }

    public function test_normal_user_can_not_see_index()
    {
        $this->actingAsUser();
        $this->get(route('role-permissions.index'))->assertStatus(403);
    }

    public function test_permitted_user_can_store_roles()
    {
        $rand = rand(1,1000);
        $this->actingAsAdminRole();
        $this->post(route('role-permissions.store'), [
            'name' => "super admin $rand",
            'permissions' => [
                Permission::PERMISSION_MANAGE_COURSES,
                Permission::PERMISSION_MANAGE_CATEGORIES,
                Permission::PERMISSION_TEACH,
            ]
        ])->assertRedirect(route('role-permissions.index'));
        $this->assertEquals(count(Role::$roles) + 1, Role::count());  //migration seed -> default exit role teacher
    }

    public function test_normal_user_can_not_store_roles()
    {
        $rand = rand(1,1000);
        $this->actingAsUser();
        $this->post(route('role-permissions.store'), [
            'name' => "super admin $rand",
            'permissions' => [
                Permission::PERMISSION_MANAGE_COURSES,
                Permission::PERMISSION_MANAGE_CATEGORIES,
                Permission::PERMISSION_TEACH,
            ]
        ])->assertStatus(403);
        $this->assertEquals(count(Role::$roles), Role::count());  //1(role default teach) == 1
    }

    public function test_permitted_user_can_see_edit()
    {
        $this->actingAsAdminRole();
        $role = $this->createRole();
        $this->get(route('role-permissions.edit', $role->id))->assertOk();
    }

    public function test_normal_user_can_not_see_edit()
    {
        $this->actingAsAdminRole();
        $role = $this->createRole();
        $this->actingAsUser();
        $this->get(route('role-permissions.edit', $role->id))->assertStatus(403);
    }


    public function test_permitted_user_can_update_roles()
    {
        $this->actingAsAdminRole();
        $role = $this->createRole();
        $this->patch(route('role-permissions.update', $role->id), [
            'name' => 'Role updated',
            "permissions" => [
                Permission::PERMISSION_MANAGE_COURSES,
                Permission::PERMISSION_MANAGE_CATEGORIES,
                Permission::PERMISSION_TEACH,]
        ])->assertRedirect(route('role-permissions.index'));
        $this->assertEquals('Role updated', $role->fresh()->name);
    }

    public function test_normal_user_can_not_update_roles()
    {
        $this->actingAsUser();
        $role = $this->createRole();
        $this->patch(route('role-permissions.update', $role->id), [
            'name' => 'Role updated',
            "permissions" => [
                Permission::PERMISSION_MANAGE_COURSES,
                Permission::PERMISSION_MANAGE_CATEGORIES,
                Permission::PERMISSION_TEACH,]
        ])->assertStatus(403);
        $this->assertEquals($role->name, $role->fresh()->name);
    }

    public function test_permitted_user_can_delete_roles()
    {
        $this->actingAsAdminRole();
        $role = $this->createRole();
        $this->delete(route('role-permissions.destroy', $role->id))/*->assertOk()*/;
        $this->assertEquals(count(Role::$roles) , Role::count());
    }

    public function test_normal_user_can_not_delete_roles()
    {
        $this->actingAsUser();
        $role = $this->createRole();
        $this->delete(route('role-permissions.destroy', $role->id))->assertStatus(403);
        $this->assertEquals(count(Role::$roles) + 1 , Role::count());
    }


    /*----- Create User -----*/
    public function actingAsUser()
    {
        $this->actingAs(User::factory()->create());
        $this->seed(RolePermissionTableSeeder::class);
    }

    public function actingAsSuperAdmin()
    {
        $this->actingAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_SUPER_ADMIN);
    }

    public function actingAsAdminRole()
    {
        $this->actingAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_ROLE_PERMISSION);
    }

    public function createRole()
    {
        $rand = rand(1,1000);
        return Role::create(['name' => "Role test $rand"])->syncPermissions
        ([
            Permission::PERMISSION_MANAGE_COURSES,
            Permission::PERMISSION_MANAGE_CATEGORIES,
            Permission::PERMISSION_TEACH,
        ]);
    }

}
