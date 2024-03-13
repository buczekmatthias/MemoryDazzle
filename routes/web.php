<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')->prefix('security')->name('security.')->controller(SecurityController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'handleLogin');

    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'handleRegister');

    Route::get('/reset-password', 'resetPassword')->name('resetPassword');
});


Route::middleware('auth')->group(function () {
    Route::get('/security/logout', [SecurityController::class, 'logout'])->name('logout');

    Route::controller(AppController::class)->group(function () {
        Route::get('/', 'homepage')->name('homepage');
        Route::get('/download/{postFile}', 'downloadFile')->name('download');
    });

    Route::controller(PostController::class)->name('posts.')->prefix('/posts')->group(function () {
        Route::post('/store', 'store')->name('store');
        Route::delete('/delete/{post}', 'delete')->name('delete');
        Route::get('/edit/{post}', 'edit')->name('edit');
        Route::post('/edit/{post}', 'handleEdit');
        Route::get('/{post}', 'view')->name('view');
    });

    Route::controller(CommentController::class)->name('comments.')->prefix('/comments')->group(function () {
        Route::post('/create', 'createComment')->name('create');
        Route::delete('/delete/{comment}', 'deleteComment')->name('delete');
    });

    Route::controller(ReactionController::class)->name('reactions.')->prefix('/reactions')->group(function () {
        Route::post('/{post}/add', 'addReaction')->name('add');
        Route::delete('/{post_id}/{reaction_name}/remove', 'removeReaction')->name('remove');
    });

    Route::controller(GroupController::class)->name('groups.')->prefix('/groups')->group(function () {
        Route::post('/create', 'createGroup')->name('create');
        Route::get('/{group}', 'view')->name('view');
        Route::get("/{group}/edit", 'edit')->name('edit');
        Route::post("/{group}/edit", 'handleEdit');
        Route::delete("/{group}/delete", 'handleDelete');
    });

    Route::controller(UserController::class)->name('user.')->group(function () {
        Route::get('/edit-profile', 'profileEdit')->name('profile.edit');
        Route::post('/edit-profile', 'handleProfileEdit');
        Route::delete('/delete-profile', 'deleteProfile');
        Route::get('/users', 'usersList')->name('users');
        Route::post('/follow/{user:username}', 'handleFollow')->name('follow');
        Route::prefix('/requests')->name('requests')->group(function () {
            Route::get('/', 'listFollowRequests')->name('list');
            Route::post('/{action}/{user}', 'handleFollowRequests')->name('handle');
        });
        Route::get('/{user:username}', 'profile')->name('profile');
        Route::get('/{user:username}/followers', 'followers')->name('followers');
    });
});
