<?php

namespace Webamooz\Comment\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Webamooz\Category\Models\Category;
use Webamooz\Comment\Models\Comment;
use Webamooz\Course\Models\Course;
use Webamooz\RolePermissions\Database\Seeders\RolePermissionTableSeeder;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\User\Models\User;

class CommentTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    //---------- index
    public function test_permitted_user_can_see_comments_index()
    {
        $this->actingAsSuperAdmin();
        $this->get(route('comments.index'))->assertOk();

        $this->actingAsAdminComment();
        $this->get(route('comments.index'))->assertOk();
    }


    public function test_normal_user_can_not_see_comments_index()
    {
        $this->actingAsUser();
        $this->get(route('comments.index'))->assertStatus(403);
    }

    //---------- store
    public function test_user_can_store_comment()
    {
        $this->actingAsUser();  //یک کاربر ساده میتونه کامنت بزاره
        $course = $this->createCourse();

        $this->post(route('comments.store', [
            'user_id' => auth()->user()->id,
            'comment_id' => null,  //comment because comment_id null
            'commentable_id' => $course->id,
            'commentable_type' => get_class($course),
            'body' => $this->faker->text(250),
            'status' => Comment::STATUS_NEW,
        ]))->assertRedirect();

        $this->assertEquals(1, Comment::all()->count());
    }

    //---------- reply
    public function test_user_can_not_reply_to_unapproved_comment()
    {
        $this->actingAsUser();
        $course = $this->createCourse();
        //----- create comment
        $this->post(route('comments.store', [
            'user_id' => auth()->user()->id,
            'comment_id' => null,  //comment because comment_id null
            'commentable_id' => $course->id,
            'commentable_type' => get_class($course),
            'body' => $this->faker->text(250),
            'status' => Comment::STATUS_NEW,
        ]));

        //----- create reply comment
        $this->post(route('comments.store', [
            'user_id' => auth()->user()->id,
            'comment_id' => 1,  //reply comment
            'commentable_id' => $course->id,
            'commentable_type' => get_class($course),
            'body' => $this->faker->text(250),
            'status' => Comment::STATUS_NEW,
        ]));
        $this->assertEquals(1, Comment::all()->count());  //فقط درون جدول کامنت ما ثبت شده نه پاسخ چون وضعیتش تایید شده نیست
    }

    public function test_permitted_user_can_reply_to_approved_comment()
    {
        $this->actingAsAdminComment();
        $course = $this->createCourse();
        //----- create comment
        $this->post(route('comments.store', [
            'user_id' => auth()->user()->id,
            'comment_id' => null,  //comment because comment_id null
            'commentable_id' => $course->id,
            'commentable_type' => get_class($course),
            'body' => $this->faker->text(250),
            'status' => auth()->user()->can(Permission::PERMISSION_MANAGE_COMMENTS) || auth()->user()->can(Permission::PERMISSION_TEACH)
                ? Comment::STATUS_APPROVED : Comment::STATUS_NEW,
        ]));

        //----- create reply comment
        $this->post(route('comments.store', [
            'user_id' => auth()->user()->id,
            'comment_id' => 1,  //reply comment
            'commentable_id' => $course->id,
            'commentable_type' => get_class($course),
            'body' => $this->faker->text(250),
            'status' => auth()->user()->can(Permission::PERMISSION_MANAGE_COMMENTS) || auth()->user()->can(Permission::PERMISSION_TEACH)
                ? Comment::STATUS_APPROVED : Comment::STATUS_NEW,
        ]));
        $this->assertEquals(2, Comment::all()->count());  //هم کامنت ساختیم و هم پاسخ دادیم و هر دو وضعیت تایید شده
    }

    //---------- show list
    public function test_permitted_user_can_show_list_comments()
    {
        $this->actingAsAdminComment();
        $course = $this->createCourse();
        //----- create comment
        $this->post(route('comments.store', [
            'user_id' => auth()->user()->id,
            'comment_id' => null,  //comment because comment_id null
            'commentable_id' => $course->id,
            'commentable_type' => get_class($course),
            'body' => $this->faker->text(250),
            'status' => auth()->user()->can(Permission::PERMISSION_MANAGE_COMMENTS) || auth()->user()->can(Permission::PERMISSION_TEACH)
                ? Comment::STATUS_APPROVED : Comment::STATUS_NEW,
        ]))->assertRedirect();

        $this->assertEquals(1, Comment::query()->count());

        $this->delete(route('comments.show', 1))->assertOk();
    }

    public function test_user_can_not_show_list_comments()
    {
        $this->actingAsUser();
        $course = $this->createCourse();
        //----- create comment
        $this->post(route('comments.store', [
            'user_id' => auth()->user()->id,
            'comment_id' => null,  //comment because comment_id null
            'commentable_id' => $course->id,
            'commentable_type' => get_class($course),
            'body' => $this->faker->text(250),
            'status' => auth()->user()->can(Permission::PERMISSION_MANAGE_COMMENTS) || auth()->user()->can(Permission::PERMISSION_TEACH)
                ? Comment::STATUS_APPROVED : Comment::STATUS_NEW,
        ]))->assertRedirect();

        $this->assertEquals(1, Comment::query()->count());

        $this->delete(route('comments.show', 1))->assertStatus(403);
    }

    //---------- delete
    public function test_permitted_user_can_delete_comment()
    {
        $this->actingAsAdminComment();
        $course = $this->createCourse();
        //----- create comment
        $this->post(route('comments.store', [
            'user_id' => auth()->user()->id,
            'comment_id' => null,  //comment because comment_id null
            'commentable_id' => $course->id,
            'commentable_type' => get_class($course),
            'body' => $this->faker->text(250),
            'status' => auth()->user()->can(Permission::PERMISSION_MANAGE_COMMENTS) || auth()->user()->can(Permission::PERMISSION_TEACH)
                ? Comment::STATUS_APPROVED : Comment::STATUS_NEW,
        ]))->assertRedirect();

        $this->assertEquals(1, Comment::query()->count());

        $this->delete(route('comments.destroy', 1))->assertOk();
        $this->assertEquals(0, Comment::all()->count());
    }

    public function test_user_can_not_delete_comment()
    {
        $this->actingAsUser();
        $course = $this->createCourse();
        //----- create comment
        $this->post(route('comments.store', [
            'user_id' => auth()->user()->id,
            'comment_id' => null,  //comment because comment_id null
            'commentable_id' => $course->id,
            'commentable_type' => get_class($course),
            'body' => $this->faker->text(250),
            'status' => auth()->user()->can(Permission::PERMISSION_MANAGE_COMMENTS) || auth()->user()->can(Permission::PERMISSION_TEACH)
                ? Comment::STATUS_APPROVED : Comment::STATUS_NEW,
        ]))->assertRedirect();

        $this->assertEquals(1, Comment::query()->count());

        $this->delete(route('comments.destroy', 1))->assertStatus(403);
        $this->assertEquals(1, Comment::get()->count());
    }

    //---------- change status
    public function test_permitted_user_can_change_status_comment()
    {
        $this->actingAsAdminComment();
        $course = $this->createCourse();
        //----- create comment
        $comment = Comment::create([
            'user_id' => auth()->user()->id,
            'comment_id' => null,  //comment because comment_id null
            'commentable_id' => $course->id,
            'commentable_type' => get_class($course),
            'body' => $this->faker->text(250),
            'status' => auth()->user()->can(Permission::PERMISSION_MANAGE_COMMENTS) || auth()->user()->can(Permission::PERMISSION_TEACH)
                ? Comment::STATUS_APPROVED : Comment::STATUS_NEW,
        ]);

        $this->patch(route('comments.accept', $comment->id), [
            'status' => Comment::STATUS_APPROVED,
        ])->assertOk();

        $this->patch(route('comments.reject', $comment->id), [
            'status' => Comment::STATUS_REJECTED,
        ])->assertOk();
    }

    public function test_user_can_not_change_status_comment()
    {
        $this->actingAsUser();
        $course = $this->createCourse();
        //----- create comment
        $comment = Comment::create([
            'user_id' => auth()->user()->id,
            'comment_id' => null,  //comment because comment_id null
            'commentable_id' => $course->id,
            'commentable_type' => get_class($course),
            'body' => $this->faker->text(250),
            'status' => auth()->user()->can(Permission::PERMISSION_MANAGE_COMMENTS) || auth()->user()->can(Permission::PERMISSION_TEACH)
                ? Comment::STATUS_APPROVED : Comment::STATUS_NEW,
        ]);

        $this->patch(route('comments.accept', $comment->id), [
            'status' => Comment::STATUS_APPROVED,
        ])->assertStatus(403);

        $this->patch(route('comments.reject', $comment->id), [
            'status' => Comment::STATUS_REJECTED,
        ])->assertStatus(403);
    }

    //-------------------- User Create
    public function createUSer()
    {
        $this->actingAs(User::factory()->create());  //actingAs -> authentication + factory(User::class))->create() -> create user
    }

    private function actingAsSuperAdmin()
    {
        $this->createUSer();
        $this->seed(RolePermissionTableSeeder::class);  //create permission + role
        auth()->user()->givePermissionTo(Permission::PERMISSION_SUPER_ADMIN);
    }

    public function actingAsAdminComment()
    {
        $this->createUSer();
        $this->seed(RolePermissionTableSeeder::class);
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_COMMENTS);
    }

    public function actingAsUser()
    {
        $this->createUSer();
        $this->seed(RolePermissionTableSeeder::class);  //create permission + role
    }

    private function createTicket()
    {
        return Comment::create([
            'user_id' => auth()->user()->id,
            'comment_id' => null,
            'commentable_id' => 1,
            'commentable_type' => 'Webamooz\Course\Models\Course',
            'body' => $this->faker->text(),
            'status' => auth()->user()->can(Permission::PERMISSION_MANAGE_COMMENTS) || auth()->user()->can(Permission::PERMISSION_TEACH)
                ? Comment::STATUS_APPROVED : Comment::STATUS_NEW,
        ]);
//      $this->post(route('tickets.store'), ['title' => $this->faker->title(), 'course' => $this->faker->numberBetween(1, 4), 'body' => $this->faker->unique()->text()]);
    }


    //---------- Create Category
    public function createCategory()
    {
        return Category::create(['title' => $this->faker->title, 'slug' => $this->faker->slug]);
    }

    //---------- Create Course
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
