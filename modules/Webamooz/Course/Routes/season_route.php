<?php

use Webamooz\Course\Http\Controllers\SeasonController;

Route::group(['namespace' => 'Webamooz\Course\Http\Controllers', 'middleware' => ['web', 'auth', 'verified']], function ($router) {
    $router->post('/seasons/{course_id}', [SeasonController::class, 'store'])->name('seasons.store');
    $router->get('/seasons/{season}/edit', [SeasonController::class, 'edit'])->name('seasons.edit');
    $router->patch('/seasons/{season}', [SeasonController::class, 'update'])->name('seasons.update');
    $router->delete('/seasons/{season}', [SeasonController::class, 'destroy'])->name('seasons.destroy');

    $router->patch('/seasons/{season}/accept', [SeasonController::class, 'accept'])->name('seasons.accept');
    $router->patch('/seasons/{season}/reject', [SeasonController::class, 'reject'])->name('seasons.reject');
    $router->patch('/seasons/{season}/lock', [SeasonController::class, 'lock'])->name('seasons.lock');
    $router->patch('/seasons/{season}/unlock', [SeasonController::class, 'unlock'])->name('seasons.unlock');
});

