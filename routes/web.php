<?php

use App\Http\Controllers\FacebookController;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwitterController;
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

        Route::get('/page-tokens/create', [FacebookController::class, 'createPageToken'])->name('facebook.pages.create');
        Route::post('/page-tokens/', [FacebookController::class, 'savePageToken'])->name('facebook.pages.store');
    });

    Route::prefix('instagram')->group(function () {
        Route::get('/posts', [InstagramController::class, 'index'])->name('instagram.posts.index');
        Route::get('/connect', [InstagramController::class, 'connect'])->name('instagram.facebook.connect');
        Route::post('/connect/store', [InstagramController::class, 'connectToFacebook'])->name('instagram.facebook.connect.store');
        Route::get('/posts/create', [InstagramController::class, 'create'])->name('instagram.posts.create');
        Route::post('/posts/store', [InstagramController::class, 'store'])->name('instagram.posts.store');
    });

    Route::prefix('twitter')->group(function () {
        Route::get('/tweets', [TwitterController::class, 'index'])->name('twitter.tweets.index');
        Route::get('/tweets/create', [TwitterController::class, 'create'])->name('twitter.tweets.create');
        Route::post('/tweets/store', [TwitterController::class, 'store'])->name('twitter.tweets.store');
    });
});

require __DIR__.'/auth.php';