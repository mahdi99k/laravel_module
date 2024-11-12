<?php

namespace Webamooz\Category\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Webamooz\Category\Models\Category;
use Webamooz\Category\Policies\CategoryPolicy;
use Webamooz\RolePermissions\Model\Permission;

class CategoryServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/category_route.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views/', 'Category');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/');
        Gate::policy(Category::class, CategoryPolicy::class);
    }

    public function boot()
    {  //$this->app->booted(function () {-> check see work true
        config()->set('sidebar.items.categories', [
            "icon" => "i-categories",
            "title" => "دسته بندی ها",
            "url" => route('categories.index'),
            "permission" => Permission::PERMISSION_MANAGE_CATEGORIES,
        ]);
    }

}
