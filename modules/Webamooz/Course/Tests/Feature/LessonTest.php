<?php

namespace Webamooz\Course\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;
use Webamooz\Category\Models\Category;
use Webamooz\Course\Models\Lesson;
use Webamooz\Course\Models\Season;
use Webamooz\RolePermissions\Database\Seeders\RolePermissionTableSeeder;
use Webamooz\Course\Models\Course;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\User\Models\User;

class LessonTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    public function test_user_can_see_create_lesson_form()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $this->get(route('lessons.create', $course->id))->assertOk();

        $this->actingAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course = $this->createCourse();
        $this->get(route('lessons.create', $course->id))->assertOk();
    }

    public function test_normal_user_can_not_see_create_lesson_form()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();

        $this->actingAsUser();
        $this->get(route('lessons.create', $course->id))->assertStatus(403);
    }

    public function test_permitted_user_can_store_lesson()
    {
        $this->actingAsSuperAdmin();  //actingAsAdminCourse -> این میزنوشتم خطا میداد
        $course = $this->createCourse();

        $this->post(route('lessons.store', $course->id), [
            'title' => "lesson one",
            'time' => "20",
            'is_free' => 1,
            'lesson_file' => UploadedFile::fake()->create('lesson.mp4', 10240),
        ]);
        $this->assertEquals(1, Lesson::query()->count());
    }

    public function test_only_allowed_extensions_can_be_uploaded()
    {
        $notAllowedExtensions = ['jpg', 'jpeg', 'png', 'svg', 'mp3'];
        $this->actingAsAdminCourse();
        $course = $this->createCourse();

        foreach ($notAllowedExtensions as $extension) {
            $this->post(route('lessons.store', $course->id), [
                'title' => "lesson one",
                'time' => "20",
                'is_free' => 1,
                'lesson_file' => UploadedFile::fake()->create('lesson.' . $extension, 10240),
            ]);
        }

        $this->assertEquals(0, Lesson::query()->count());
    }


    public function test_permitted_user_can_edit_lesson()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);
        $this->get(route('lessons.edit', [$course->id, $lesson->id]))->assertOk();

        $this->actingAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);
        $this->get(route('lessons.edit', [$course->id, $lesson->id]))->assertOk();

        //----- test_normal_user_can_not_edit_lesson
        $this->actingAsUser();  //$course -> برای این کاربر نیست
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->get(route('lessons.edit', [$course->id, $lesson->id]))->assertStatus(403);
    }

    public function test_permitted_user_can_update_lesson()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);

        $this->patch(route('lessons.update', [$course->id, $lesson->id]), [
            "title" => "updated title",
            "time" => "15",
            "is_free" => 0
        ]);
        $this->assertEquals("updated title", Lesson::find(1)->title);
//      $this->assertEquals("updated title", $lesson->fresh()->title);

        //----- test_normal_user_can_not_update_course_other
        $this->actingAsUser();  //user can not updated course other
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->patch(route('lessons.update', [$course->id, $lesson->id]), [
            "title" => "updated title 2",
            "time" => "15",
            "is_free" => 0
        ])->assertStatus(403);
        $this->assertEquals('updated title', Lesson::query()->first()->title);
    }

    public function test_permitted_user_can_destroy_lesson()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);
        $lesson2 = $this->createLesson($course);

        $this->delete(route('lessons.destroy', [$course->id, $lesson->id]))->assertOk();
        $this->assertEquals(null, Lesson::find(1));
//      $this->assertEquals(null , Lesson::first());

        //---------- test_normal_user_can_not_destroy_lesson
        $this->actingAsUser();
        $this->delete(route('lessons.destroy', [$course->id, $lesson2->id]))->assertStatus(403);
        $this->assertEquals(1, Lesson::find(2)->count());

        //---------- test_owner_course_can_not_destroy_lesson
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->delete(route('lessons.destroy', [$course->id, $lesson2->id]))->assertStatus(403);
        $this->assertEquals(1, Lesson::where('id', 2)->count());
    }


    public function test_permitted_user_can_destroy_multiple_lessons()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);
        $lesson2 = $this->createLesson($course);

        $course2 = $this->createCourse();
        $lesson3 = $this->createLesson($course2);

        $this->delete(route('lessons.destroyMultiple', $course->id), [
            'lesson_ids' => '1,2'
        ]);
        $this->assertEquals(null, Lesson::find(1));
        $this->assertEquals(null, Lesson::find(2));
        $this->assertEquals(3, Lesson::find(3)->id);

        //---------- test_normal_user_can_not_destroy_lesson
        $this->actingAsUser();
        $this->delete(route('lessons.destroyMultiple', $course->id), [
            'lesson_ids' => '3'
        ])->assertStatus(403);
        $this->assertEquals(3, Lesson::where('id', 3)->first()->id);

        //---------- test_owner_course_can_not_destroy_lesson
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->delete(route('lessons.destroyMultiple', $course->id), [
            'lesson_ids' => '3'
        ])->assertStatus(403);
        $this->assertEquals(1, Lesson::find(3)->count());
    }


    public function test_permitted_user_can_accept_lesson()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::first()->confirmation_status);
        $this->patch(route('lessons.accept', [$lesson->id]));  //route -> با صدا زدن این روت خودش آپدیت میکنه به تایید شده
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_ACCEPTED, Lesson::find(1)->confirmation_status);

        //---------- test_normal_user_can_not_update_accept
        $this->actingAsUser();
        $this->patch(route('lessons.accept', [$lesson->id]))->assertStatus(403);

        //---------- test_owner_course_can_not_update_accept
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);  //دوره فرد دیگه ای میخواد آتایید کنه که دسترسی ندارد
        $this->patch(route('lessons.accept', $lesson->id))->assertStatus(403);
    }

    public function test_permitted_user_can_accept_multiple_lessons()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);
        $lesson2 = $this->createLesson($course);
        $lesson3 = $this->createLesson($course);

        $this->patch(route('lessons.acceptMultiple', $course->id), [
            'lesson_ids' => '1,2'
        ]);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_ACCEPTED, Lesson::first()->confirmation_status);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_ACCEPTED, Lesson::find(2)->confirmation_status);;
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(3)->confirmation_status);

        //---------- test_normal_user_can_not_accept_multiple
        $this->actingAsUser();
        $this->patch(route('lessons.acceptMultiple', $course->id), [
            'lesson_ids' => '3'
        ])->assertStatus(403);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(3)->confirmation_status);

        //---------- test_owner_course_can_not_accept_multiple
        auth()->user()->givePErmissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->patch(route('lessons.acceptMultiple', $course->id), [
            'lesson_ids' => '3',
        ])->assertStatus(403);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(3)->confirmation_status);
    }

    public function test_permitted_user_can_accept_all_lessons()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);
        $lesson2 = $this->createLesson($course);

        $course2 = $this->createCourse();
        $lesson3 = $this->createLesson($course2);

        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(1)->confirmation_status);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(2)->confirmation_status);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(3)->confirmation_status);

        $this->patch(route('lessons.acceptAll', $course->id));
        $this->assertEquals($course->lessons()->count(),
            $course->lessons()->where('confirmation_status', Lesson::CONFIRMATION_STATUS_ACCEPTED)->count()
        );

        $this->assertEquals($course2->lessons()->count(),
            $course2->lessons()->where('confirmation_status', Lesson::CONFIRMATION_STATUS_PENDING)->count()
        );

        //---------- test_normal_user_can_not_accept_all
        $this->actingAsUser();  //just access -> PERMISSION_MANAGE_COURSES
        $this->patch(route('lessons.acceptAll', $course2->id))->assertStatus(403);
        $this->assertEquals($course2->lessons()->count(),
            $course2->lessons()->where('confirmation_status', Lesson::CONFIRMATION_STATUS_PENDING)->count()
        );

        //---------- test_owner_course_can_not_accept_all
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->patch(route('lessons.acceptAll', $course2->id))->assertStatus(403);
        $this->assertEquals($course2->lessons()->count(),
            $course2->lessons()->where('confirmation_status', Lesson::CONFIRMATION_STATUS_PENDING)->count()
        );
    }

    public function test_permitted_user_can_reject_lesson()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::first()->confirmation_status);
        $this->patch(route('lessons.reject', $lesson->id));
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_REJECTED, Lesson::first()->confirmation_status);

        //---------- test_normal_user_can_not_reject
        $this->actingAsUser();
        $this->patch(route('lessons.reject', $lesson->id))->assertStatus(403);

        //---------- test_normal_user_can_not_reject
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->patch(route('lessons.reject', $lesson->id))->assertStatus(403);
    }

    public function test_permitted_user_can_reject_multiple_lessons()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $lesson1 = $this->createLesson($course);
        $lesson2 = $this->createLesson($course);
        $lesson3 = $this->createLesson($course);

        $this->patch(route('lessons.rejectMultiple', $course), [
            'lesson_ids' => '1,2'
        ]);

        $this->assertEquals(Lesson::CONFIRMATION_STATUS_REJECTED, Lesson::first()->confirmation_status);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_REJECTED, Lesson::find(2)->confirmation_status);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::where('id', 3)->first()->confirmation_status);

        //---------- test_normal_user_can_not_reject_multiple
        $this->actingAsUser();
        $this->patch(route('lessons.rejectMultiple', $course->id), [
            'lesson_ids' => '3'
        ])->assertStatus(403);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(3)->confirmation_status);

        //---------- test_normal_user_can_not_reject_multiple
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->patch(route('lessons.rejectMultiple', $course->id), [
            'lesson_ids' => '3'
        ])->assertStatus(403);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(3)->confirmation_status);
    }

    public function test_permitted_user_can_lock_lesson()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);
        $lesson2 = $this->createLesson($course);

        $this->patch(route('lessons.lock', $lesson->id));  //not request -> پس نسازی نیست پارامترزی بفرستیم
        $this->assertEquals(Lesson::STATUS_LOCKED, Lesson::first()->status);
        $this->assertEquals(Lesson::STATUS_OPENED, Lesson::find(2)->status);

        //---------- test_normal_user_can_not_lock
        $this->actingAsUser();
        $this->patch(route('lessons.lock', $lesson2->id))->assertStatus(403);
        $this->assertEquals(Lesson::STATUS_OPENED, Lesson::find(2)->status);

        //---------- test_normal_user_can_not_lock
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->patch(route('lessons.lock', $lesson2->id))->assertStatus(403);
        $this->assertEquals(Lesson::STATUS_OPENED, Lesson::where('id', 2)->first()->status);
    }

    public function test_permitted_user_can_unlock_lesson()
    {
        $this->actingAsAdminCourse();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);
        $lesson2 = $this->createLesson($course);
        $this->patch(route('lessons.lock', $lesson->id));
        $this->patch(route('lessons.lock', $lesson2->id));
        $this->assertEquals(Lesson::STATUS_LOCKED, Lesson::find(1)->status);
        $this->assertEquals(Lesson::STATUS_LOCKED, Lesson::find(2)->status);


        $this->patch(route('lessons.unlock', $lesson->id));  //not request -> پس نسازی نیست پارامترزی بفرستیم
        $this->assertEquals(Lesson::STATUS_OPENED, Lesson::find(1)->status);
        $this->assertEquals(Lesson::STATUS_LOCKED, Lesson::find(2)->status);

        //---------- test_normal_user_lessons.unlock
        $this->actingAsUser();
        $this->patch(route('lessons.unlock', $lesson2->id))->assertStatus(403);
        $this->assertEquals(Lesson::STATUS_LOCKED, Lesson::find(2)->status);

        //---------- test_normal_user_lessons.unlock
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->patch(route('lessons.unlock', $lesson2->id))->assertStatus(403);
        $this->assertEquals(Lesson::STATUS_LOCKED, Lesson::where('id', 2)->first()->status);
    }

    /*---------- Create User ----------*/

    private function createLesson($course)
    {
        return Lesson::create([
            "course_id" => $course->id,
            "user_id" => auth()->id(),
            "title" => $this->faker->title,
            "slug" => $this->faker->slug,
            "time" => $this->faker->numberBetween(5, 25),
        ]);
    }

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
