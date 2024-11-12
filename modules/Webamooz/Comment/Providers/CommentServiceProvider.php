<?php

namespace Webamooz\Comment\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webamooz\Comment\Models\Comment;
use Webamooz\Comment\Policies\CommentPolicy;
use Webamooz\RolePermissions\Model\Permission;

class CommentServiceProvider extends ServiceProvider
{

    protected string $namespace = "Webamooz\Comment\Http\Controllers";

    public function register()
    {
        $this->app->register(EventServiceProvider::class);  //add serviceProvider in app
        Route::middleware(['web', 'auth'])->namespace($this->namespace)->group(__DIR__ . '/../Routes/comments_routes.php');
//      $this->loadRoutesFrom(__DIR__ . '/../Routes/comments_routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views/', 'Comments');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../Lang/');
        Gate::policy(Comment::class, CommentPolicy::class);  //1)Model 2)Policy
    }

    public function boot()
    {
        config()->set('sidebar.items.comments', [
            "icon" => "i-comments",
            "title" => "نظرات کاربران",
            "url" => route('comments.index'),
            "permission" => [Permission::PERMISSION_MANAGE_COMMENTS, Permission::PERMISSION_TEACH]
        ]);
    }
}
