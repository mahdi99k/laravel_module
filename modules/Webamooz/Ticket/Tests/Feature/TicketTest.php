<?php

namespace Webamooz\Ticket\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use Webamooz\Category\Models\Category;
use Webamooz\RolePermissions\Database\Seeders\RolePermissionTableSeeder;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\Ticket\Models\Ticket;
use Webamooz\User\Models\User;

class TicketTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    public function test_user_can_see_tickets()
    {
        $this->actingAsUser();
        $this->get(route('tickets.index'))->assertOk();
    }

    public function test_user_can_see_create()
    {
        $this->actingAsUser();
        $this->get(route('tickets.create'))->assertOk();
    }

    public function test_user_can_store_ticket()
    {
        $this->actingAsUser();
        $this->createTicket();
        $this->assertEquals(1, Ticket::get()->count());
    }

    public function test_permitted_user_can_delete_ticket()
    {
        $this->actingAsAdmin();
        $this->createTicket();
        $this->assertEquals(1, Ticket::get()->count());

        $this->delete(route('tickets.destroy', 1))->assertOk();
    }

    public function test_user_can_not_delete_ticket()
    {
        $this->actingAsUser();
        $this->createTicket();
        $this->assertEquals(1, Ticket::get()->count());

        $this->delete(route('tickets.destroy', 1))->assertStatus(403);
    }


    //-------------------- User Create
    private function actingAsAdmin()
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
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_TICKETS);
    }

    public function actingAsUser()
    {
        $this->actingAs(User::factory()->create());  //actingAs -> authentication + factory(User::class))->create() -> create user
        $this->seed(RolePermissionTableSeeder::class);  //create permission + role
    }

    private function createTicket()
    {
        Ticket::create([
            'user_id' => auth()->user()->id,
            'title' => $this->faker->title(),
            'ticketable_id' => $this->faker->numberBetween(1, 4),  //Relationship One To Many
            'ticketable_type' => 'Webamooz\Course\Models\Course',  //object + Relationship One To Many
        ]);
//      $this->post(route('tickets.store'), ['title' => $this->faker->title(), 'course' => $this->faker->numberBetween(1, 4), 'body' => $this->faker->unique()->text()]);
    }

}
