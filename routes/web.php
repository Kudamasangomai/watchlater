<?php

use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\WatchLaterVideosController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {return redirect()->route('login'); });

Route::get('auth/google', [ GoogleLoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleLoginController::class, 'callback']);

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('reminders',ReminderController::class);
    Route::get('/watchlater', [WatchLaterVideosController::class, 'index']);
});

require __DIR__.'/auth.php';
