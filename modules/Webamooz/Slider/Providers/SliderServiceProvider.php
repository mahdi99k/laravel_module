<?php

namespace Webamooz\Slider\Providers;

use Illuminate\Support\Facades\Route;
use Webamooz\RolePermissions\Model\Permission;

class SliderServiceProvider extends \Illuminate\Support\ServiceProvider
{

    private $namespace = "Webamooz\Slider\Http\Controllers";

    public function register()
    {
        Route::middleware(['web'])->namespace($this->namespace)->group(__DIR__ . '/../Routes/slider_routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views/', 'Slides');
    }

    public function boot()
    {
        config()->set('sidebar.items.sliders', [
            "icon" => "i-slide",
            "title" => "اسلایدر",
            "url" => route('slides.index'),
            "permission" => Permission::PERMISSION_MANAGE_SLIDES
        ]);
    }

}
