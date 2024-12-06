<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\PostController as UserPostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register')
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('categories', CategoryController::class);
Route::resource('posts', PostController::class);
Route::post('posts/{id}/restore', [PostController::class, 'restore'])->name('posts.restore');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('posts', PostController::class);
    Route::post('posts/{id}/restore', [PostController::class, 'restore'])->name('posts.restore');
    Route::resource('tags', TagController::class);
});

Route::middleware(['auth', 'role:author'])->group(function () {
    Route::get('user/posts', [UserPostController::class, 'index'])->name('user.posts.index');
    Route::get('user/posts/create', [UserPostController::class, 'create'])->name('user.posts.create');
    Route::post('user/posts', [UserPostController::class, 'store'])->name('user.posts.store');
    Route::get('user/posts/{id}/edit', [UserPostController::class, 'edit'])->name('user.posts.edit');
    Route::put('user/posts/{id}', [UserPostController::class, 'update'])->name('user.posts.update');
    Route::delete('user/posts/{id}', [UserPostController::class, 'destroy'])->name('user.posts.destroy');
});

require __DIR__.'/auth.php';
