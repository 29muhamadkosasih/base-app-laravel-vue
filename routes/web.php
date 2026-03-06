<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Support\AuthRedirect;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $redirectRoute = AuthRedirect::routeName(auth()->user());

    if (!$redirectRoute) {
        abort(403, 'Akun Anda belum memiliki dashboard yang dapat diakses.');
    }

    return redirect()->route($redirectRoute);
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', LoginController::class)->name('login.post');
});

Route::post('/logout', LogoutController::class)
    ->middleware('auth')
    ->name('logout');

Route::prefix('admin')
    ->as('admin.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/dashboard', DashboardController::class)
            ->middleware('permission:dashboard.view')
            ->name('dashboard');

        Route::get('/profile', ProfileController::class)
            ->name('profile');

        Route::get('/settings', [SettingController::class, 'edit'])
            ->middleware('permission:settings.index')
            ->name('settings.edit');
        Route::post('/settings', [SettingController::class, 'update'])
            ->middleware('permission:settings.index')
            ->name('settings.update');

        Route::resource('users', UserController::class)
            ->only(['index'])
            ->middleware('permission:users.view');
        Route::resource('users', UserController::class)
            ->only(['create', 'store'])
            ->middleware('permission:users.create');
        Route::resource('users', UserController::class)
            ->only(['edit', 'update'])
            ->middleware('permission:users.edit');
        Route::resource('users', UserController::class)
            ->only(['destroy'])
            ->middleware('permission:users.delete');

        Route::resource('roles', RoleController::class)
            ->only(['index'])
            ->middleware('permission:roles.view');
        Route::resource('roles', RoleController::class)
            ->only(['create', 'store'])
            ->middleware('permission:roles.create');
        Route::resource('roles', RoleController::class)
            ->only(['edit', 'update'])
            ->middleware('permission:roles.edit');
        Route::resource('roles', RoleController::class)
            ->only(['destroy'])
            ->middleware('permission:roles.delete');

        Route::resource('permissions', PermissionController::class)
            ->only(['index'])
            ->middleware('permission:permissions.view');
        Route::resource('permissions', PermissionController::class)
            ->only(['create', 'store'])
            ->middleware('permission:permissions.create');
        Route::resource('permissions', PermissionController::class)
            ->only(['edit', 'update'])
            ->middleware('permission:permissions.edit');
        Route::resource('permissions', PermissionController::class)
            ->only(['destroy'])
            ->middleware('permission:permissions.delete');
    });
