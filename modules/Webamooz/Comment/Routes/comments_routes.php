<?php

use Illuminate\Support\Facades\Route;
use Webamooz\Comment\Http\Controllers\CommentController;


//---------- Front Site
Route::group([], function ($router) {  //commentable -> این کامنت میتونه برای قسمت های مختلفی باش مثل دوره و وبلاگ و آیدی و اسم مادل میگیره و پولی مورفیسم
    $router->resource('comments', CommentController::class);
});


//---------- Panel Admin
Route::group([], function ($router) {
    $router->get('/comments', ['uses' => 'CommentController@index', 'as' => 'comments.index']);
    $router->patch('/comments/{comment}/reject', [CommentController::class, 'reject'])->name('comments.reject');
    $router->patch('/comments/{comment}/accept', [CommentController::class, 'accept'])->name('comments.accept');
});







