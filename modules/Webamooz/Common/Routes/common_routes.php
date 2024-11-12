<?php

use Webamooz\Common\Http\Controllers\NotificationController;

Route::group(['middleware' => ['auth']], function ($router) {
    $router->get('notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
});
