<?php

namespace Webamooz\Course\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;
use Webamooz\Category\Models\Category;
use Webamooz\RolePermissions\Database\Seeders\RolePermissionTableSeeder;
use Webamooz\Course\Models\Course;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\User\Models\User;

class CourseTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    //permitted user can se course index
    public function test_permitted_user_can_see_courses_index()
    {
        $this->actingAsAdminCourse();
        $this->get(route('courses.index'))->assertOk();

        $this->actingAsSuperAdmin();
        $this->get(route('courses.index'))->assertOk();
    }

    public function test_normal_user_can_not_see_courses_index()
    {
        $this->actingAsUser();
        $this->get(route('courses.index'))->assertStatus(403);
    }

    //permitted user can create course
    public function test_permitted_user_can_create_course()
    {
        $this->actingAsAdminCourse();
        $this->get(route('courses.create'))->assertOk();

        $this->actingAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->get(route('courses.create'))->assertOk();
    }

    public function test_normal_user_can_not_create_course()
    {
        $this->actingAsUser();
        $this->get(route('courses.create'))->assertStatus(403);
    }

    //permitted user can store course
    public function test_permitted_user_can_store_course()
    {
        $this->actingAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES, Permission::PERMISSION_TEACH);
        \Storage::fake('local');

        $this->post(route('courses.store'), $this->courseData())->assertRedirect(route('courses.index'));
        $this->assertEquals(Course::count(), 1);;
    }


    //permitted user can edit course
    public function test_permitted_user_can_edit_course()
    {
        $this->withoutExceptionHandling();
        $this->actingAsAdminCourse();  //همه دوره ها میتونه بررسی کنه و تغییر بده
        $course = $this->createCourse();
        $this->get(route('courses.edit', $course->id))->assertOk();

        $this->actingAsUser();
        $course = $this->createCourse();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->get(route('courses.edit', $course->id))->assertOk();  //پرمیژن تغییرات دوره های من + دوره که خودش ساخته میاد ویرایش میکنه
    }

    public function test_permitted_user_can_not_edit_other_users_course()
    {
        $this->actingAsUser();
        $course = $this->createCourse();  //دوره کاربر دیگر

        $this->actingAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);  //سظح دسترسی صاحب دوره های من دارداما آیدی دوره فرد دیگری میخواد ویرایش کنه
        $this->get(route('courses.edit', $course))->assertStatus(403);
    }

    public function test_normal_user_can_not_edit_course()
    {
        $this->actingAsUser();
        $course = $this->createCourse();
        $this->get(route('courses.edit', $course->id))->assertStatus(403);
    }


    //permitted user can update course
    public function test_permitted_user_can_updated_course()
    {
        $this->withoutExceptionHandling();
        $this->actingAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES, Permission::PERMISSION_TEACH);
        $course = $this->createCourse();
        $this->patch(route('courses.update', $course->id), [
            'teacher_id' => auth()->id(),
            'category_id' => $course->category->id,
            'title' => 'updated title',
            'slug' => 'updated slug',
            'priority' => 10,
            'price' => 1500000,
            'percent' => 50,
            'type' => Course::TYPE_CASH,
            'status' => Course::STATUS_LOCKED,
            "image" => UploadedFile::fake()->image('banner.jpg', 150, 200),  //send file in testing -> UploadedFile
//          "pdf" => UploadedFile::fake()->create('test.pdf' , 120 , 'pdf'),  //send other file(pdf,word,excel,powerPoint,video,music) in testing
            'body' => $this->faker->sentence(4),
        ])->assertRedirect(route('courses.index'));
        $course = $course->fresh();  //get end data -> بیا دوره که آپدیت کردیم بگیر آخرین دیتاهایی که تغییر دادیم نه دوره ای که ساتختیم
        $this->assertEquals('updated title', $course->title);
    }

    public function test_normal_user_can_not_update_course()
    {
        $this->actingAsUser();  //user 1
        $course = $this->createCourse();  //دوره برای کاربر شماره یک و کاربر دوم مجوز تدریس داره و این دوره برای ون نیست

        $this->actingAsUser();  //user 2
        auth()->user()->givePermissionTo(Permission::PERMISSION_TEACH);  //به کاربر دومی مجوز تدریس داد
        $this->patch(route('courses.update', $course->id), [
            'teacher_id' => auth()->id(),
            'category_id' => $course->category->id,
            'title' => 'updated title',
            'slug' => 'updated slug',
            'priority' => 10,
            'price' => 1500000,
            'percent' => 50,
            'type' => Course::TYPE_CASH,
            'status' => Course::STATUS_LOCKED,
            "image" => UploadedFile::fake()->image('banner.jpg', 150, 200),  //send file in testing -> UploadedFile
//          "pdf" => UploadedFile::fake()->create('test.pdf' , 120 , 'pdf'),  //send other file(pdf,word,excel,powerPoint,video,music) in testing
            'body' => $this->faker->sentence(4),
        ])->assertStatus(403);
    }


    //permitted user can delete course
    public function test_permitted_user_can_delete_course()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();

        $this->delete(route('courses.destroy', $course->id))->assertOk();
        $this->assertEquals(0, $course->count());  //deleted
    }

    public function test_normal_user_can_not_delete_course()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $this->actingAsUser();
        $this->delete(route('courses.destroy', $course->id))->assertStatus(403);
        $this->assertEquals(1, Course::count());
    }

    //permitted user can (accept,reject,lock) course
    public function test_permitted_user_can_change_confirmation_status_course()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();

        $this->patch(route('courses.accept', $course->id), [
            'confirmation_status' => Course::CONFIRMATION_STATUS_PENDING,
        ])->assertOk();

        $this->patch(route('courses.reject', $course->id), [
            'confirmation_status' => Course::CONFIRMATION_STATUS_PENDING,
        ])->assertOk();

        $this->patch(route('courses.lock', $course->id), [
            'confirmation_status' => Course::CONFIRMATION_STATUS_PENDING,
        ])->assertOk();
    }

    public function test_normal_user_can_not_change_confirmation_status_course()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();

        $this->actingAsUser();
        $this->patch(route('courses.accept', $course->id), [
            'confirmation_status' => Course::CONFIRMATION_STATUS_ACCEPTED,
        ])->assertStatus(403);

        $this->patch(route('courses.reject', $course->id), [
            'confirmation_status' => Course::CONFIRMATION_STATUS_ACCEPTED,
        ])->assertStatus(403);

        $this->patch(route('courses.lock', $course->id), [
            'confirmation_status' => Course::CONFIRMATION_STATUS_ACCEPTED,
        ])->assertStatus(403);
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
