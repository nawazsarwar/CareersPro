<?php

declare(strict_types=1);

use App\Http\Controllers\Frontend\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Frontend\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Frontend\Auth\EmailVerificationController;
use App\Http\Controllers\Frontend\Auth\MobileVerificationController;
use App\Http\Controllers\Frontend\Auth\NewPasswordController;
use App\Http\Controllers\Frontend\Auth\OtpLoginController;
use App\Http\Controllers\Frontend\Auth\PasswordResetLinkController;
use App\Http\Controllers\Frontend\Auth\RegisteredUserController;
use App\Http\Controllers\Frontend\Auth\TwoFactorChallengeController;
use App\Http\Controllers\Frontend\Auth\TwoFactorSettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend routes — M03 §4
|--------------------------------------------------------------------------
|
| Sign-in is one form with one identifier field (DR-008): applicants type an
| email, staff an email or an employee ID, and the credential resolver decides
| the column. There is no branch in the UI and no separate staff login.
|
*/

Route::middleware('guest')->group(function (): void {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store'])->middleware('throttle:6,1');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:6,1');

    // The OTP path is a secondary submit on the same card, not a second screen.
    Route::post('login/otp', [OtpLoginController::class, 'store'])
        ->name('login.otp.request')
        ->middleware('throttle:6,1');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email')
        // The named limiter, not a bare throttle: it is keyed on the address
        // as well as the IP, which is what stops slow enumeration.
        ->middleware('throttle:password-reset');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store')
        ->middleware('throttle:6,1');
});

/*
| The half-authenticated window between the first factor and the challenge.
| `auth` would reject these users and `guest` would admit anyone, so the
| pending state needs a middleware of its own.
*/
Route::middleware('auth.pending')->group(function (): void {
    Route::get('login/otp/verify', [OtpLoginController::class, 'create'])->name('login.otp.verify');
    Route::post('login/otp/verify', [OtpLoginController::class, 'verify'])->middleware('throttle:6,1');
    Route::post('login/otp/resend', [OtpLoginController::class, 'resend'])
        ->name('login.otp.resend')
        ->middleware('throttle:3,60');

    Route::get('two-factor/challenge', [TwoFactorChallengeController::class, 'create'])->name('two-factor.challenge');
    Route::post('two-factor/challenge', [TwoFactorChallengeController::class, 'store'])->middleware('throttle:6,1');
    Route::post('two-factor/challenge/resend', [TwoFactorChallengeController::class, 'resend'])
        ->name('two-factor.challenge.resend')
        ->middleware('throttle:3,60');
});

Route::middleware('auth')->group(function (): void {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Ending an impersonation is reachable from the impersonated session, so
    // it cannot live behind the admin stack the impersonated user fails.
    Route::delete('impersonate', [App\Http\Controllers\Admin\ImpersonationController::class, 'destroy'])
        ->name('impersonate.stop');

    Route::get('verify-email', [EmailVerificationController::class, 'prompt'])->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('profile/mobile/otp', [MobileVerificationController::class, 'send'])
        ->name('profile.mobile.otp')
        ->middleware('throttle:5,60');
    Route::post('profile/mobile/verify', [MobileVerificationController::class, 'verify'])
        ->name('profile.mobile.verify')
        ->middleware('throttle:5,60');

    Route::middleware('password.confirm')->group(function (): void {
        Route::get('settings/two-factor', [TwoFactorSettingsController::class, 'index'])->name('two-factor.index');
        Route::post('settings/two-factor/{type}', [TwoFactorSettingsController::class, 'store'])->name('two-factor.store');
        Route::post('settings/two-factor/{type}/confirm', [TwoFactorSettingsController::class, 'confirm'])->name('two-factor.confirm');
        Route::delete('settings/two-factor/{type}', [TwoFactorSettingsController::class, 'destroy'])->name('two-factor.destroy');
    });
});

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::view('dashboard', 'frontend.dashboard')->name('dashboard');
});
