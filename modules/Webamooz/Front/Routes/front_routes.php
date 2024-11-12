<?php

use Webamooz\Front\Http\Controllers\FrontController;

Route::group(['namespace' => 'Webamooz\Front\Http\Controllers', 'middleware' => ['web']], function ($router) {
    $router->get('/', [FrontController::class, 'index'])->name('website');
    $router->get('/c-{slug}', [FrontController::class, 'singleCourse'])->name('courses.singleCourse');
    $router->get('/tutors/{username}', [FrontController::class, 'singleTutors'])->name('singleTutors');
});


