<?php

use Webamooz\Media\Http\Controllers\MediaController;

Route::group([], function ($router) {
    $router->get('/media/{media}/download', [MediaController::class , 'download'])->name('media.download');
});
