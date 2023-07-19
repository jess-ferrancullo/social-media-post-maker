<?php

use App\Http\Controllers\FacebookController;
use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('facebook')->group(function () {
        Route::get('/posts', [FacebookController::class, 'index'])->name('facebook.posts.index');
        Route::get('/posts/create', [FacebookController::class, 'create'])->name('facebook.posts.create');
        Route::post('/posts/store', [FacebookController::class, 'store'])->name('facebook.posts.store');
    });
});

require __DIR__.'/auth.php';