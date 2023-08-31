<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Subscribe\IndexController as SubscribeController;
use App\Http\Controllers\Subscribe\PayPalController;
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

Route::group([
    'domain' => 'www.labelsync.it',
], function () {
    Route::get('language/{locale}', [HomeController::class, 'setLanguage'])->name('language');
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('contact', [HomeController::class, 'contact'])->name('contact');
    Route::get('terms', [HomeController::class, 'terms'])->name('terms');
    Route::get('privacy', [HomeController::class, 'privacy'])->name('privacy');

    Route::group([
    ], function () {
        Route::post('subscribe', [SubscribeController::class, 'subscribe'])->name('subscribe');
        Route::get('subscribe/cancel', [SubscribeController::class, 'cancel'])->name('subscribe.cancel');

        Route::get('subscribe/paypal/success', [PayPalController::class, 'success'])->name('subscribe.paypal.success');
        Route::post('subscribe/paypal/webhook', [PayPalController::class, 'webhook'])->name('subscribe.paypal.webhook');
    });
});


Route::group([
    'domain' => 'admin.labelsync.it',
    //'prefix' => 'admin',
    'as' => 'admin.'
], function () {
    Route::group([
        'middleware' => ['guest:admin'],
    ], function () {
        Route::get('login', [AuthController::class, 'login'])->name('login');
        Route::post('login', [AuthController::class, 'postLogin']);
        Route::get('forgot-password', [AuthController::class, 'forgot'])->name('password.forgot');
        Route::post('forgot-password', [AuthController::class, 'postForgot']);
        Route::get('reset-password/{token}', [AuthController::class, 'reset'])->name('password.reset');
        Route::post('reset-password', [AuthController::class, 'postReset'])->name('password.update');
    });

    Route::group([
        'middleware' => ['auth:admin'],
    ], function () {
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        })->name('home');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('licenses', LicenseController::class);

        Route::resource('memberships', MembershipController::class);

        Route::resource('transactions', TransactionController::class)->only(['index']);

        Route::resource('contacts', ContactController::class)->only(['index', 'edit', 'update']);

        Route::resource('settings', SettingsController::class)->only(['index', 'store']);
        Route::post('update-theme', [SettingsController::class, 'updateTheme'])->name('update-theme');

        Route::get('profile', [ProfileController::class, 'index'])->name('profile');
        Route::post('update-email', [ProfileController::class, 'updateEmail'])->name('update-email');
        Route::post('update-password', [ProfileController::class, 'updatePassword'])->name('update-password');

        Route::get('logout', function () {
            auth()->logout();
            return redirect()->route('admin.login');
        })->name('logout');
    });
});
