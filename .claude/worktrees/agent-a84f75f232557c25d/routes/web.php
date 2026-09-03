<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Wave 0 ships no routes. Every route this application had resolved into the
| generated scaffolding removed in this wave, and five of them referenced a
| class name whose namespace separators had been stripped, so they registered
| silently and failed at dispatch.
|
| Routes return in Wave 1 (M03 — identity), declared per module against the
| build specifications in docs/v3/02-plan/, under the Admin and Frontend
| namespaces required by docs/v3/01-design/engineering-standards.md §2.
|
*/

Route::view('/', 'welcome')->name('home');
