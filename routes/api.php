<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1/auth'

], function ($router) {
    Route::post('login', [\App\Http\Controllers\Api\V1\AuthController::class, 'login'])->name('auth.login');
    Route::post('logout', [\App\Http\Controllers\Api\V1\AuthController::class, 'logout'])->name('auth.logout');
    Route::post('refresh', [\App\Http\Controllers\Api\V1\AuthController::class, 'refresh'])->name('auth.refresh');
    Route::get('me', [\App\Http\Controllers\Api\V1\AuthController::class, 'me'])->name('auth.me');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1/lawyer'
], function ($router) {
    Route::get('', [\App\Http\Controllers\Api\V1\LawyerController::class, 'index'])->name('lawyers.all');
    Route::get('/{id}', [\App\Http\Controllers\Api\V1\LawyerController::class, 'show'])->name('lawyers.get');
    Route::post('', [\App\Http\Controllers\Api\V1\LawyerController::class, 'store'])->name('lawyers.store');
    Route::put('/{id}', [\App\Http\Controllers\Api\V1\LawyerController::class, 'update'])->name('lawyers.update');
    Route::delete('/{id}', [\App\Http\Controllers\Api\V1\LawyerController::class, 'destroy'])->name('lawyers.destroy');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1/customer'
], function ($router) {
    Route::get('', [\App\Http\Controllers\Api\V1\CustomerController::class, 'index'])->name('customers.all');
    Route::get('/{id}', [\App\Http\Controllers\Api\V1\CustomerController::class, 'show'])->name('customers.get');
    Route::post('', [\App\Http\Controllers\Api\V1\CustomerController::class, 'store'])->name('customers.store');
    Route::put('/{id}', [\App\Http\Controllers\Api\V1\CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/{id}', [\App\Http\Controllers\Api\V1\CustomerController::class, 'destroy'])->name('customers.destroy');
});
