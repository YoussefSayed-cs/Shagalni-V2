<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});


Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::middleware('can:viewAny,App\Models\User')->group(function () {
            Route::get('/users', [UserController::class, 'index'])
                ->name('users.index');
            Route::get('/users/{user}', [UserController::class, 'show'])
                ->name('users.show');

            Route::put('/users/{user}', [UserController::class, 'update'])
                ->name('users.update');

            Route::patch('/users/{user}/active', [UserController::class, 'toggleActive'])
                ->name('users.toggle-active');
        });
    });


require __DIR__ . '/settings.php';
