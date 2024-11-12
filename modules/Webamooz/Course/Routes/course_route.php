<?php

use Webamooz\Course\Http\Controllers\CourseController;

Route::group(['namespace' => 'Webamooz\Course\Http\Controllers', 'middleware' => ['web', 'auth', 'verified']], function ($router) {
    $router->resource('/courses', CourseController::class);
    $router->patch('/courses/{course}/accept', [CourseController::class, 'accept'])->name('courses.accept');
    $router->patch('/courses/{course}/reject', [CourseController::class, 'reject'])->name('courses.reject');
    $router->patch('/courses/{course}/lock', [CourseController::class, 'lock'])->name('courses.lock');
    $router->get('/courses/{course}/details', [CourseController::class, 'details'])->name('courses.details');
    $router->post('/courses/{course}/buy', [CourseController::class, 'buy'])->name('courses.buy');
    $router->get('/courses/{course}/download_all_links', [CourseController::class, 'downloadAllLinks'])->name('courses.downloadAllLinks');
});

