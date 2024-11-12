<?php

use Webamooz\Course\Http\Controllers\LessonController;

Route::group(['namespace' => 'Webamooz\Course\Http\Controllers', 'middleware' => ['web', 'auth', 'verified']], function ($router) {
    $router->get('/courses/{course}/lesson/create', [LessonController::class, 'create'])->name('lessons.create');
    $router->post('/courses/{course}/lesson', [LessonController::class, 'store'])->name('lessons.store');
    $router->get('/courses/{course}/lesson/{id}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
    $router->patch('/courses/{course}/lesson/{id}', [LessonController::class, 'update'])->name('lessons.update');
    $router->delete('/courses/{course}/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');
    $router->delete('/courses/{course}/lessons', [LessonController::class, 'destroyMultiple'])->name('lessons.destroyMultiple');

    $router->patch('/courses/{course}/lessons/accept-all', [LessonController::class, 'acceptAll'])->name('lessons.acceptAll');
    $router->patch('/courses/{course}/lessons/accept-multiple', [LessonController::class, 'acceptMultiple'])->name('lessons.acceptMultiple');
    $router->patch('/courses/{course}/lessons/reject-multiple', [LessonController::class, 'rejectMultiple'])->name('lessons.rejectMultiple');


    $router->patch('/lessons/{lesson}/accept', [LessonController::class, 'accept'])->name('lessons.accept');
    $router->patch('/lessons/{lesson}/reject', [LessonController::class, 'reject'])->name('lessons.reject');
    $router->patch('/lessons/{lesson}/lock', [LessonController::class, 'lock'])->name('lessons.lock');
    $router->patch('/lessons/{lesson}/unlock', [LessonController::class, 'unlock'])->name('lessons.unlock');
});

