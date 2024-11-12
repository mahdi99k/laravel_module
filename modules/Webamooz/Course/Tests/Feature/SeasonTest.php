<?php

namespace Webamooz\Course\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;
use Webamooz\Category\Models\Category;
use Webamooz\Course\Models\Season;
use Webamooz\RolePermissions\Database\Seeders\RolePermissionTableSeeder;
use Webamooz\Course\Models\Course;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\User\Models\User;

class SeasonTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_permitted_user_can_see_course_details_page()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $this->get(route('courses.details', $course->id))->assertOk();

        $this->actingAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course->teacher_id = auth()->id();  //کاربر ساده مجوز صاحب دوره دادم و کردمش صاحب دوره
        $course->save();
        $this->get(route('courses.details', $course->id))->assertOk();

        $this->actingAsSuperAdmin();
        $this->get(route('courses.details', $course->id))->assertOk();
    }

    public function test_not_permitted_user_can_not_see_course_details_page()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();

        $this->actingAsUser();
        $this->get(route('courses.details', $course->id))->assertStatus(403);

        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->get(route('courses.details', $course->id))->assertStatus(403);
    }

    public function test_permitted_user_can_create_season()
    {
//      $this->withoutExceptionHandling();
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $this->post(route('seasons.store', $course->id), [
            'title' => 'introduction laravel',
            'number' => '1'
        ]);
        $this->assertEquals(1, Season::count());

        $this->actingAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course->teacher_id = auth()->id();  //کاربر ساده مجوز صاحب دوره دادم و کردمش صاحب دوره
        $course->save();
        $this->post(route('seasons.store', $course->id), [
            'title' => 'introduction laravel 2', //number -> automatic 2
        ]);
        $this->assertEquals(2, Season::count());
        $this->assertEquals(2, Season::find(2)->number);
    }

    public function test_not_permitted_user_can_not_create_season()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();

        $this->actingAsUser();
        $this->post(route('seasons.store', $course->id), [
            'title' => 'introduction laravel 1', //number -> automatic 2
        ])->assertStatus(403);

        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->post(route('seasons.store', $course->id), [
            'title' => 'introduction laravel 2', //number -> automatic 2
        ])->assertStatus(403);
    }

    public function test_permitted_user_can_edit_season()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $this->post(route('seasons.store', $course->id), [
            'title' => 'introduction laravel',
            'number' => '1'
        ]);
        $this->get(route('seasons.edit', 1))->assertOk();

        $this->actingAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course->teacher_id = auth()->user()->id;
        $course->save();
        $this->get(route('seasons.edit', 1))->assertOk();
    }

    public function test_not_permitted_user_can_not_see_edit_season()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $this->post(route('seasons.store', $course->id), [
            'title' => 'introduction laravel',
            'number' => '1'
        ]);
        $this->get(route('seasons.edit', 1))->assertOk();

        $this->actingAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->get(route('seasons.edit', 1))->assertStatus(403);
    }

    public function test_permitted_user_can_update_season()
    {
//      $this->withoutExceptionHandling();
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $this->post(route('seasons.store', $course->id), [
            'title' => 'introduction laravel',
            'number' => '1'
        ]);
        $this->assertEquals(1, Season::count());

        $this->patch(route('seasons.update', 1), [
            'title' => 'routing laravel'
        ]);
        $this->assertEquals('routing laravel', Season::find(1)->title);

        //----- owner course
        $this->actingAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course->teacher_id = auth()->id();  //کاربر ساده مجوز صاحب دوره دادم و کردمش صاحب دوره
        $course->save();
        $this->patch(route('seasons.update', 1), [
            'title' => 'model laravel',
            'number' => 5
        ]);
        $this->assertEquals('5', Season::find(1)->number);
    }


    public function test_not_permitted_user_can_not_update_season()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $this->post(route('seasons.store', $course->id), [
            'title' => 'introduction laravel',
            'number' => '1'
        ]);
        $this->assertEquals(1, Season::count());

        //----- normalUser
        $this->actingAsUser();
        $this->patch(route('seasons.update', 1), [
            'title' => 'test laravel'
        ])->assertStatus(403);
        $this->assertEquals('introduction laravel', Season::find(1)->title);

        //----- owner course but course not for you اما دوره برای خودش نیست
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->patch(route('seasons.update', 1), [
            'title' => 'test laravel'
        ])->assertStatus(403);
        $this->assertEquals('introduction laravel', Season::find(1)->title);
    }

    public function test_permitted_user_can_delete_season()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $this->post(route('seasons.store', $course->id), [
            'title' => 'introduction laravel',
            'number' => '1'
        ]);
        $this->assertEquals(1, Season::count());
        $this->delete(route('seasons.destroy', 1))->assertOk();
        $this->assertEquals(0, Season::count());

        //----- owner course
        $this->actingAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course->teacher_id = auth()->id();  //کاربر ساده مجوز صاحب دوره دادم و کردمش صاحب دوره
        $course->save();
        $this->post(route('seasons.store', $course->id), [
            'title' => 'introduction laravel',
            'number' => '2'
        ]);
        $this->delete(route('seasons.destroy', 2))->assertOk();
        $this->assertEquals(0, Season::count());
    }

    public function test_not_permitted_user_can_not_delete_season()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $this->post(route('seasons.store', $course->id), [
            'title' => 'introduction laravel',
            'number' => '1'
        ]);
        $this->assertEquals(1, Season::count());

        //----- normalUser
        $this->actingAsUser();
        $this->delete(route('seasons.destroy', 1))->assertStatus(403);
        $this->assertEquals(1, Season::count());

        //----- permission ownerCourse + but not have -> اما صاحب دوره نیست
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->delete(route('seasons.destroy', 1))->assertStatus(403);
        $this->assertEquals(1, Season::count());
    }

    public function test_permitted_user_can_accept_season()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $this->post(route('seasons.store', $course->id), [
            'title' => 'introduction laravel',
            'number' => '1'
        ]);
        $this->assertEquals(1, Season::count());

        $this->assertEquals(Season::CONFIRMATION_STATUS_PENDING, Season::find(1)->confirmation_status);
        $this->patch(route('seasons.accept', 1))->assertOk();
        $this->assertEquals(Season::CONFIRMATION_STATUS_ACCEPTED, Season::find(1)->confirmation_status);

        //----- owner course + فقط مدیر دوره دسترسی دارد نه صاحب دوره
        $this->actingAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course->teacher_id = auth()->id();  //کاربر ساده مجوز صاحب دوره دادم و کردمش صاحب دوره
        $course->save();
        $this->post(route('seasons.store', $course->id), [
            'title' => 'Route laravel',
            'number' => '2'
        ]);

        $this->assertEquals(Season::CONFIRMATION_STATUS_PENDING, Season::find(2)->confirmation_status);
        $this->patch(route('seasons.accept', 2))->assertStatus(403);
        $this->assertEquals(Season::CONFIRMATION_STATUS_PENDING, Season::find(2)->confirmation_status);
    }


    public function test_permitted_user_can_reject_season()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $this->post(route('seasons.store', $course->id), [
            'title' => 'introduction laravel',
            'number' => '1'
        ]);
        $this->assertEquals(1, Season::count());

        $this->assertEquals(Season::CONFIRMATION_STATUS_PENDING, Season::find(1)->confirmation_status);
        $this->patch(route('seasons.reject', 1))->assertOk();
        $this->assertEquals(Season::CONFIRMATION_STATUS_REJECTED, Season::find(1)->confirmation_status);

        //----- owner course + فقط مدیر دوره دسترسی دارد نه صاحب دوره
        $this->actingAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course->teacher_id = auth()->id();  //کاربر ساده مجوز صاحب دوره دادم و کردمش صاحب دوره
        $course->save();
        $this->post(route('seasons.store', $course->id), [
            'title' => 'Route laravel',
            'number' => '2'
        ]);

        $this->assertEquals(Season::CONFIRMATION_STATUS_PENDING, Season::find(2)->confirmation_status);
        $this->patch(route('seasons.reject', 2))->assertStatus(403);
        $this->assertEquals(Season::CONFIRMATION_STATUS_PENDING, Season::find(2)->confirmation_status);
    }

    public function test_permitted_user_can_lock_season()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $this->post(route('seasons.store', $course->id), [
            'title' => 'introduction laravel',
            'number' => '1'
        ]);
        $this->assertEquals(1, Season::count());

        $this->assertEquals(Season::STATUS_OPENED, Season::find(1)->status);
        $this->patch(route('seasons.lock', 1))->assertOk();
        $this->assertEquals(Season::STATUS_LOCKED, Season::find(1)->status);

        $this->patch(route('seasons.unlock', 1))->assertOk();
        //----- owner course + فقط مدیر دوره دسترسی دارد نه صاحب دوره
        $this->actingAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course->teacher_id = auth()->id();  //کاربر ساده مجوز صاحب دوره دادم و کردمش صاحب دوره
        $course->save();
        $this->assertEquals(Season::STATUS_OPENED, Season::find(1)->status);
        $this->patch(route('seasons.lock', 1))->assertStatus(403);
        $this->assertEquals(Season::STATUS_OPENED, Season::find(1)->status);
    }

    public function test_permitted_user_can_unlock_season()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $this->post(route('seasons.store', $course->id), [
            'title' => 'introduction laravel',
            'number' => '1'
        ]);
        $this->assertEquals(1, Season::count());
        $this->patch(route('seasons.lock', 1))->assertOk();


        $this->assertEquals(Season::STATUS_LOCKED, Season::find(1)->status);
        $this->patch(route('seasons.unlock', 1))->assertOk();
        $this->assertEquals(Season::STATUS_OPENED, Season::find(1)->status);


        $this->patch(route('seasons.lock', 1))->assertOk();
        //----- owner course + فقط مدیر دوره دسترسی دارد نه صاحب دوره
        $this->actingAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course->teacher_id = auth()->id();  //کاربر ساده مجوز صاحب دوره دادم و کردمش صاحب دوره
        $course->save();
        $this->assertEquals(Season::STATUS_LOCKED, Season::find(1)->status);
        $this->patch(route('seasons.unlock', 1))->assertStatus(403);
        $this->assertEquals(Season::STATUS_LOCKED, Season::find(1)->status);
    }

    /*---------- Create User ----------*/
    public function createUser()
    {
        $this->actingAs(User::factory()->create());  //actingAs -> authentication + factory(User::class))->create() -> create user
        $this->seed(RolePermissionTableSeeder::class);
    }

    public function actingAsSuperAdmin()
    {
        $this->createUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_SUPER_ADMIN);
    }

    public function actingAsAdminCourse()
    {
        $this->createUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_COURSES);
    }

    public function actingAsUser()
    {
        $this->createUser();
    }

    /*---------- Create Data ----------*/
    public function createCategory()
    {
        return Category::create(['title' => $this->faker->title, 'slug' => $this->faker->slug]);
    }

    public function createCourse()
    {
        $data = $this->courseData() + ['confirmation_status' => Course::CONFIRMATION_STATUS_PENDING,];
        unset($data['image']);
        return Course::create($data);
    }

    public function courseData()
    {
        $category = $this->createCategory();
        return [
            'teacher_id' => auth()->id(),
            'category_id' => $category->id,
            'title' => $this->faker->sentence(2),
            'slug' => $this->faker->sentence(2),
            'priority' => 5,
            'price' => 1000000,
            'percent' => 45,
            'type' => Course::TYPE_FREE,
            'status' => Course::STATUS_NOT_COMPLETED,
            "image" => UploadedFile::fake()->image('banner.jpg', 150, 200),  //send file in testing -> UploadedFile
//          "pdf" => UploadedFile::fake()->create('test.pdf' , 120 , 'pdf'),  //send other file(pdf,word,excel,powerPoint,video,music) in testing
            'body' => $this->faker->word,
        ];
    }

}
