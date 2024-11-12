<?php

namespace Webamooz\Course\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Webamooz\Course\Models\Course;
use Webamooz\Course\Models\Lesson;
use Webamooz\Course\Models\Season;
use Webamooz\Course\Policies\CoursePolicy;
use Webamooz\Course\Policies\LessonPolicy;
use Webamooz\Course\Policies\SeasonPolicy;
use Webamooz\RolePermissions\Model\Permission;

class CourseServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->register(EventServiceProvider::class);
        $this->loadRoutesFrom(__DIR__ . '/../Routes/course_route.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/season_route.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/lessons_route.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'Course');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../Lang/');  //file ترجمه کلممات به صورت جیسون
        $this->loadTranslationsFrom(__DIR__ . '/../Lang/', 'Course');  //lang فایل ترجمه ولیدیشن زبان های محتلف
        Gate::policy(Course::class, CoursePolicy::class);  //Model::class , ModelPolicy::class
        Gate::policy(Season::class, SeasonPolicy::class);
        Gate::policy(Lesson::class, LessonPolicy::class);
    }

    public function boot()
    {
        config()->set('sidebar.items.courses', [
            'icon' => 'i-courses',
            'title' => 'دوره ها',
            'url' => route('courses.index'),
            'permission' => [
                Permission::PERMISSION_MANAGE_COURSES,
                Permission::PERMISSION_MANAGE_OWN_COURSES
            ]
        ]);
    }

}
