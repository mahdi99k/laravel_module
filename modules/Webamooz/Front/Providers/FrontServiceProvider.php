<?php

namespace Webamooz\Front\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Webamooz\Category\Repositories\CategoryRepository;
use Webamooz\Course\Repositories\CourseRepository;
use Webamooz\Slider\Database\Repositories\SlideRepositories;

class FrontServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . "/../Routes/front_routes.php");
        $this->loadViewsFrom(__DIR__ . "/../Resources/Views/", "Front");
        view()->composer('Front::layouts.header', function ($view) {  //shared in view 'Front::layouts.header'
            $categories = (new CategoryRepository())->tree();
            $view->with(compact('categories'));
        });

        view()->composer('Front::layouts.newestCourses', function ($view) {  //shared in view 'Front::layouts.newestCourses'
            $newestCourses = (new CourseRepository())->newestCourses();
            $view->with(compact('newestCourses'));
        });

        view()->composer('Front::layouts.slider', function ($view) {
            $slides = (new SlideRepositories())->all();
            $view->with(compact('slides'));
        });
    }

    public function boot(): void
    {
        /*
        //with('subCategories') کوری ما همراه ریلیشن میگیره و مادل، دیتابیس سبک + دلیل دو بار نوشتن استفاده دوبار از ریلیشن در فرانت در فورایچ
        $categories = (new CategoryRepository())->tree();
        View::share([  //shared all views site
            'categories' => $categories
        ]);*/
    }

}
