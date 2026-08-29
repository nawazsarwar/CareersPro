<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
|
| Registered with the `admin` prefix, the `admin.` name prefix and the
| auth / verified / two-factor middleware stack, so no route in this file
| repeats them.
|
| M25 lands the roles these are authorised against, and M26's audit screen
| follows it.
|
*/

Route::view('/', 'admin.dashboard')->name('home');
