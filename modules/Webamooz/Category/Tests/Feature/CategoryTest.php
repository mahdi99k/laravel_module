<?php

namespace Webamooz\Category\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use Webamooz\Category\Models\Category;
use Webamooz\RolePermissions\Database\Seeders\RolePermissionTableSeeder;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\User\Models\User;

class CategoryTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    public function test_permitted_user_can_see_categories_panel()
    {
        $this->actingAsAdminCreate();  //create user auth + create permission
        $this->get(route('categories.index'))->assertOk();
    }

    public function test_normal_user_can_not_see_categories_panel()
    {
        $this->actingAsUserCreate();
        $this->get(route('categories.index'))->assertStatus(403);
    }

    public function test_permitted_user_can_create_category()
    {
        $this->withoutExceptionHandling();  //show all exception
        $this->actingAsAdminCreate();  //create user auth + create permission
        $this->createCategory();
        $this->assertEquals(1, Category::all()->count());
    }

    public function test_permitted_user_can_update_category()
    {
        $newTitle = 'titleUpdated';
        $this->actingAsAdminCreate();  //create user auth + create permission
        $this->createCategory();
        $this->assertEquals(1, Category::all()->count());
        $this->patch(route('categories.update', 1), ['title' => $newTitle, 'slug' => $this->faker->word]);
        $this->assertEquals(1, Category::whereTitle($newTitle)->count());
    }

    public function test_permitted_user_can_delete_category()
    {
        $this->actingAsAdminCreate();  //create user auth + create permission

        $this->createCategory();
        $this->assertEquals(1, Category::all()->count());
        $this->delete(route('categories.destroy', 1))->assertOk();
    }

    //-------------------- User Create
    private function actingAsAdminCreate()
    {
        /*$user = User::create([
            'name' => 'mahdi',
            'email' => 'mahdi@gmail.com',
            'mobile' => '9398187800',
            'email_verified_at' => now(),
            'password' => \Hash::make('12aBC!@'),
            'remember_token' => Str::random(10),
        ]);*/
        $this->actingAs(User::factory()->create());  //actingAs -> authentication + factory(User::class))->create() -> create user
        $this->seed(RolePermissionTableSeeder::class);  //create permission + role
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_CATEGORIES);
    }

    public function actingAsUserCreate()
    {
        $this->seed(RolePermissionTableSeeder::class);  //create permission + role
        $this->actingAs(User::factory()->create());  //actingAs -> authentication + factory(User::class))->create() -> create user
    }

    private function createCategory()
    {
        $this->post(route('categories.store'), ['title' => $this->faker->title(), 'slug' => $this->faker->unique()->word()]);
    }

}
