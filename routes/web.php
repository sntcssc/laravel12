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

// 
use App\Livewire\Programme\ListProgrammes;
use App\Livewire\Programme\CreateProgramme as CreateProgramme;
use App\Livewire\Programme\EditProgramme as EditProgramme;
use App\Livewire\Batch\ListBatches;
use App\Livewire\Batch\CreateBatch as CreateBatch;
use App\Livewire\Batch\EditBatch as EditBatch;
use App\Livewire\Section\ListSections;
use App\Livewire\Section\CreateSection as CreateSection;
use App\Livewire\Section\EditSection as EditSection;
use App\Livewire\Student\ListStudents;
use App\Livewire\Student\Create as CreateStudent;
use App\Livewire\Student\Edit as EditStudent;
use App\Livewire\Enrollment\ListEnrollments;
use App\Livewire\Enrollment\CreateEnrollment as CreateEnrollment;
use App\Livewire\Enrollment\EditEnrollment as EditEnrollment;
use App\Livewire\Alumni\ListAlumni;
use App\Livewire\Alumni\CreateAlumni;
use App\Livewire\Alumni\EditAlumni;
use App\Livewire\Attendance\ListAttendances;
use App\Livewire\Attendance\CreateAttendance;
use App\Livewire\Attendance\EditAttendance;
use App\Livewire\Leave\ListLeaves;
use App\Livewire\Leave\CreateLeave;
use App\Livewire\Leave\EditLeave;
// ./

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


// 
    // Programme Routes
    Route::get('/programmes', ListProgrammes::class)->name('programmes.index')->middleware('permission:view programmes');
    Route::get('/programmes/create', CreateProgramme::class)->name('programmes.create')->middleware('permission:create programmes');
    Route::get('/programmes/{programme}/edit', EditProgramme::class)->name('programmes.edit')->middleware('permission:edit programmes');

    // Batch Routes
    Route::get('/batches', ListBatches::class)->name('batches.index')->middleware('permission:view batches');
    Route::get('/batches/create', CreateBatch::class)->name('batches.create')->middleware('permission:create batches');
    Route::get('/batches/{batch}/edit', EditBatch::class)->name('batches.edit')->middleware('permission:edit batches');

    // // Section Routes
    Route::get('/sections', ListSections::class)->name('sections.index')->middleware('permission:view sections');
    Route::get('/sections/create', CreateSection::class)->name('sections.create')->middleware('permission:create sections');
    Route::get('/sections/{section}/edit', EditSection::class)->name('sections.edit')->middleware('permission:edit sections');

    // // Student Routes
    Route::get('/students', ListStudents::class)->name('students.index')->middleware('permission:view students');
    Route::get('/students/create', CreateStudent::class)->name('students.create')->middleware('permission:create students');
    Route::get('/students/{student}/edit', EditStudent::class)->name('students.edit')->middleware('permission:edit students');

    // // Enrollment Routes
    Route::get('/enrollments', ListEnrollments::class)->name('enrollments.index')->middleware('permission:view enrollments');
    Route::get('/enrollments/create', CreateEnrollment::class)->name('enrollments.create')->middleware('permission:create enrollments');
    Route::get('/enrollments/{enrollment}/edit', EditEnrollment::class)->name('enrollments.edit')->middleware('permission:edit enrollments');

    // // Alumni Routes
    Route::get('/alumni', ListAlumni::class)->name('alumni.index')->middleware('permission:view alumni');
    Route::get('/alumni/create', CreateAlumni::class)->name('alumni.create')->middleware('permission:create alumni');
    Route::get('/alumni/{alumni}/edit', EditAlumni::class)->name('alumni.edit')->middleware('permission:edit alumni');

    // // Attendance Routes
    Route::get('/attendances', ListAttendances::class)->name('attendances.index')->middleware('permission:view attendances');
    Route::get('/attendances/create', CreateAttendance::class)->name('attendances.create')->middleware('permission:create attendances');
    Route::get('/attendances/{attendance}/edit', EditAttendance::class)->name('attendances.edit')->middleware('permission:edit attendances');

    // // Leave Routes
    Route::get('/leaves', ListLeaves::class)->name('leaves.index')->middleware('permission:view leaves');
    Route::get('/leaves/create', CreateLeave::class)->name('leaves.create')->middleware('permission:create leaves');
    Route::get('/leaves/{leave}/edit', EditLeave::class)->name('leaves.edit')->middleware('permission:edit leaves');

    // ./
});

require __DIR__.'/auth.php';
