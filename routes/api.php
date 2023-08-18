<?php

use App\Http\Controllers\Extension\V1Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*
 * Extension Route
 */
Route::group([
    'namespace' => 'Extension',
    'prefix' => 'app',
], function() {
    Route::group([
        'prefix'    => 'v1',
    ], function() {
        Route::post('submit-key', [V1Controller::class, 'submitKey']);
        Route::post('recover-key', [V1Controller::class, 'recoverKey']);
        Route::post('admin-sync', [V1Controller::class, 'adminSync']);
        Route::get('labels', [V1Controller::class, 'getLabels']);
    });
});
