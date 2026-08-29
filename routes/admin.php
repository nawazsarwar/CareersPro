<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\EstablishmentController;
use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserRoleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
|
| Registered with the `admin` prefix, the `admin.` name prefix and the
| auth / verified / two-factor stack, so no route here repeats them.
|
*/

Route::view('/', 'admin.dashboard')->name('home');

Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show');

Route::get('users', [UserRoleController::class, 'index'])->name('users.index');
Route::post('users/{user}/roles', [UserRoleController::class, 'store'])->name('users.roles.attach');
Route::delete('users/{user}/roles/{role}', [UserRoleController::class, 'destroy'])->name('users.roles.detach');

Route::get('advertisements', [AdvertisementController::class, 'index'])->name('advertisements.index');
Route::post('advertisements', [AdvertisementController::class, 'store'])->name('advertisements.store');
Route::get('advertisements/{advertisement}', [AdvertisementController::class, 'show'])->name('advertisements.show');
Route::post('advertisements/{advertisement}/publish', [AdvertisementController::class, 'publish'])->name('advertisements.publish');
Route::post('advertisements/{advertisement}/corrigenda', [AdvertisementController::class, 'corrigendum'])->name('advertisements.corrigenda');

Route::get('designations', [DesignationController::class, 'index'])->name('designations.index');
Route::post('designations', [DesignationController::class, 'store'])->name('designations.store');
Route::get('designations/{designation}', [DesignationController::class, 'show'])->name('designations.show');
Route::patch('designations/{designation}', [DesignationController::class, 'update'])->name('designations.update');

Route::get('establishment', [EstablishmentController::class, 'index'])->name('establishment.index');
Route::patch('establishment/{organisationalUnit}/{designation}', [EstablishmentController::class, 'update'])
    ->name('establishment.update');

Route::get('audit', [AuditController::class, 'index'])->name('audit.index');
Route::post('audit/verify', [AuditController::class, 'verify'])->name('audit.verify');
Route::get('audit/subject/{type}/{id}', [AuditController::class, 'subject'])->name('audit.subject');
Route::get('audit/{log}', [AuditController::class, 'show'])->name('audit.show');

// No create, update or delete route exists. Entries are written by the domain,
// never by a request (M26 §4).

// password.confirm as well as the stack above: a live session is not
// sufficient authority to become somebody else.
Route::post('impersonate/{user}', [ImpersonationController::class, 'store'])
    ->middleware('password.confirm')
    ->name('impersonate.start');
