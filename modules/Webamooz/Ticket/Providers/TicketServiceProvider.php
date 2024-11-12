<?php

namespace Webamooz\Ticket\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\Ticket\Models\Reply;
use Webamooz\Ticket\Models\Ticket;
use Webamooz\Ticket\Policies\ReplyPolicy;
use Webamooz\Ticket\Policies\TicketPolicy;

class TicketServiceProvider extends ServiceProvider
{

    private $namespace = "Webamooz\Ticket\Http\Controller";

    public function register()
    {
        Route::middleware(['web'])->namespace($this->namespace)->group(__DIR__ . '/../Routes/ticket_route.php');
//      $this->loadRoutesFrom(__DIR__ . '/../Routes/ticket_route.php');  //روش بالا بهتر چون همینجا میدلور و نیم اسپیس میدیم
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views/', "Tickets");
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../Lang/');
        Gate::policy(Ticket::class, TicketPolicy::class);  //1)Model , 2)Policy
        Gate::policy(Reply::class, ReplyPolicy::class);   //1)Model , 2)Policy
    }


    public function boot()
    {
        config()->set('sidebar.items.tickets', [
            "icon" => "i-tickets",
            "title" => "تیکت های پشتیبانی",
            "url" => route('tickets.index'),
        ]);
    }

}
