<?php

namespace Webamooz\Media\Providers;

use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{

    protected string $namespace = "Webamooz\Media\Http\Controllers";

    public function register()
    {
//      $this->loadRoutesFrom(__DIR__ . '/../Routes/media_route.php');  //need namespace + middleware
        \Route::middleware(['web'])
            ->namespace($this->namespace)
            ->group(__DIR__ . '/../Routes/media_route.php');  //not need namespace + middleware
        $this->mergeConfigFrom(__DIR__ . '/../Config/mediaFile.php', 'mediaFile');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views/', 'Media');
//      $this->loadJsonTranslationsFrom(__DIR__ . '/../Lang/');
        $this->loadTranslationsFrom(__DIR__ . '/../Lang/', 'Media');
    }

    public function boot()
    {

    }

}
