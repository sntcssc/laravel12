<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;

use App\Livewire\UserManagement;
use App\Livewire\RoleManagement;
use App\Livewire\PermissionManagement;

use App\Livewire\User\UserList;
use App\Livewire\User\CreateUser;
use App\Livewire\User\EditUser;
use App\Livewire\Role\RoleList;
use App\Livewire\Role\CreateRole;
use App\Livewire\Role\EditRole;
use App\Livewire\Permission\PermissionList;
use App\Livewire\Permission\CreatePermission;
use App\Livewire\Permission\EditPermission;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    // User Management
    // Route::get('/users', UserManagement::class)->name('users.index')->middleware('permission:user-list');
    // Route::get('/roles', RoleManagement::class)->name('roles.index')->middleware('permission:role-list');
    // Route::get('/permissions', PermissionManagement::class)->name('permissions.index')->middleware('permission:permission-list');

    Route::middleware(['permission:view-users'])->group(function () {
        Route::get('/users', UserList::class)->name('users.index');
        Route::get('/users/create', CreateUser::class)->name('users.create');
        Route::get('/users/{user}/edit', EditUser::class)->name('users.edit');
    });

    Route::middleware(['permission:view-roles'])->group(function () {
        Route::get('/roles', RoleList::class)->name('roles.index');
        Route::get('/roles/create', CreateRole::class)->name('roles.create');
        Route::get('/roles/{role}/edit', EditRole::class)->name('roles.edit');
    });

    Route::middleware(['permission:view-permissions'])->group(function () {
        Route::get('/permissions', PermissionList::class)->name('permissions.index');
        Route::get('/permissions/create', CreatePermission::class)->name('permissions.create');
        Route::get('/permissions/{permission}/edit', EditPermission::class)->name('permissions.edit');
    });
});

require __DIR__.'/auth.php';
