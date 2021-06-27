<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('login-moderate', [App\Http\Controllers\Auth\LoginModerateController::class, 'showLoginForm']);
Route::post('login-moderate', [App\Http\Controllers\Auth\LoginModerateController::class, 'login'])->name('loginModerate');
Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('show/{id}', [App\Http\Controllers\HomeController::class, 'show'])->name('show');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::middleware(['auth', 'role:user'])->prefix('admin-panel')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\HomeController::class, 'index'])->name('homeAdmin');
    Route::resource('post', App\Http\Controllers\Admin\PostController::class);
    Route::get('/send-to-moderate/{id}', [App\Http\Controllers\Admin\PostController::class, 'sendToModerate'])->name('sendToModerate');
    Route::get('contact/{id}', [App\Http\Controllers\Admin\MessageController::class, 'contact'])->name('contacts');
    Route::post('write-answer/{id}', [App\Http\Controllers\Admin\MessageController::class, 'writeAnswer'])->name('writeAnswer');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::post('write-to-author/{id}', [App\Http\Controllers\HomeController::class, 'writeToAuthor'])->name('writeToAuthor');
});

Route::middleware(['auth', 'role:moder'])->prefix('moderate')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\ModerateController::class, 'index'])->name('postModerate');
    Route::get('post/{status?}', [App\Http\Controllers\Admin\ModerateController::class, 'index'])->name('postModerate');
    Route::get('post-show/{id}', [App\Http\Controllers\Admin\ModerateController::class, 'show'])->name('postModerateShow');
    Route::post('post-update/{id}', [App\Http\Controllers\Admin\ModerateController::class, 'update'])->name('postModerateUpdate');
    Route::get('user/{status?}', [App\Http\Controllers\Admin\ModerateController::class, 'user'])->name('userModerate');
    Route::get('user-change-status/{id}/{status}', [App\Http\Controllers\Admin\ModerateController::class, 'userChangeStatus'])->name('userModerateChangeStatus');
});
