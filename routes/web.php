<?php

use App\Http\Controllers\Home\AuthController;
use App\Http\Controllers\Home\DashboardController;
use App\Http\Controllers\Home\LicenseController;
use App\Http\Controllers\Home\MembershipController;
use App\Http\Controllers\Home\TransactionController;
use App\Http\Controllers\Home\ContactController;
use App\Http\Controllers\Home\SettingsController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('contact', [HomeController::class, 'contact'])->name('contact');
Route::get('terms', [HomeController::class, 'terms'])->name('terms');
Route::get('privacy', [HomeController::class, 'privacy'])->name('privacy');


Route::group([
    'middleware' => ['guest'],
], function() {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'postLogin']);
    //Route::get('forgot-password', [AuthController::class, 'forgot'])->name('password.forgot');
    //Route::post('forgot-password', [AuthController::class, 'postForgot']);
    //Route::get('reset-password/{token}', [AuthController::class, 'reset'])->name('password.reset');
    //Route::post('reset-password', [AuthController::class, 'postReset'])->name('password.update');
});

Route::group([
    'middleware' => ['auth'],
], function() {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('licenses', LicenseController::class);

    Route::resource('memberships', MembershipController::class);

    Route::resource('transactions', TransactionController::class)->only(['index']);

    Route::resource('contacts', ContactController::class)->only(['index', 'edit', 'update']);

    Route::resource('settings', SettingsController::class)->only(['index', 'store']);
    Route::post('update-theme', [SettingsController::class, 'updateTheme'])->name('update-theme');

    Route::get('profile', [SettingsController::class, 'profile'])->name('profile');
    Route::post('update-email', [SettingsController::class, 'updateEmail'])->name('update-email');
    Route::post('update-password', [SettingsController::class, 'updatePassword'])->name('update-password');

    Route::get('logout', function() {
        auth()->logout();
        return redirect()->route('login');
    })->name('logout');
});
