<?php

use Webamooz\RolePermissions\Http\Controllers\RolePermissionController;

Route::group(['namespace' => 'Webamooz\RolePermissions\Http\Controllers', 'middleware' => ['web', 'auth', 'verified']] , function ($router) {
    $router->resource('/role-permissions' , RolePermissionController::class)->middleware('permission:manage role_permissions');
});

