<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\UserCRMController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post('/register', [UserCRMController::class, 'store']);

Route::get('/user/data', [UserCRMController::class, 'userLogged'])
    ->middleware(['auth']);

Route::put('/update-information', [RegisteredUserController::class, 'update'])
    ->middleware(['auth','permss'])
    ->name('List|config|users|register');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('auth')
    ->name('password.store');

Route::post('/reset-user-pass', [NewPasswordController::class, 'update'])
    ->middleware('auth')
    ->name('password.reset');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');


Route::post('/configure-smtp-user', [UserCRMController::class, 'editSMTPCredentials'])
    ->middleware('auth')
    ->name('logout');
