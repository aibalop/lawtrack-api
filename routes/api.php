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
    Route::get('', [\App\Http\Controllers\Api\V1\LawyerController::class, 'index'])->name('lawyer.all');
    Route::get('/{id}', [\App\Http\Controllers\Api\V1\LawyerController::class, 'show'])->name('lawyer.get');
    Route::post('', [\App\Http\Controllers\Api\V1\LawyerController::class, 'store'])->name('lawyer.store');
    Route::put('/{id}', [\App\Http\Controllers\Api\V1\LawyerController::class, 'update'])->name('lawyer.update');
    Route::delete('/{id}', [\App\Http\Controllers\Api\V1\LawyerController::class, 'destroy'])->name('lawyer.destroy');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1/customer'
], function ($router) {
    Route::get('', [\App\Http\Controllers\Api\V1\CustomerController::class, 'index'])->name('customer.all');
    Route::get('/{id}', [\App\Http\Controllers\Api\V1\CustomerController::class, 'show'])->name('customer.get');
    Route::post('', [\App\Http\Controllers\Api\V1\CustomerController::class, 'store'])->name('customer.store');
    Route::put('/{id}', [\App\Http\Controllers\Api\V1\CustomerController::class, 'update'])->name('customer.update');
    Route::delete('/{id}', [\App\Http\Controllers\Api\V1\CustomerController::class, 'destroy'])->name('customer.destroy');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1/project'
], function ($router) {
    Route::get('', [\App\Http\Controllers\Api\V1\ProjectController::class, 'index'])->name('project.all');
    Route::get('/{id}', [\App\Http\Controllers\Api\V1\ProjectController::class, 'show'])->name('project.get');
    Route::post('', [\App\Http\Controllers\Api\V1\ProjectController::class, 'store'])->name('project.store');
    Route::put('/{id}', [\App\Http\Controllers\Api\V1\ProjectController::class, 'update'])->name('project.update');
    Route::delete('/{id}', [\App\Http\Controllers\Api\V1\ProjectController::class, 'destroy'])->name('project.destroy');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1/note'
], function ($router) {
    Route::get('', [\App\Http\Controllers\Api\V1\NoteController::class, 'index'])->name('notes.all');
    Route::get('/{id}', [\App\Http\Controllers\Api\V1\NoteController::class, 'show'])->name('notes.get');
    Route::post('', [\App\Http\Controllers\Api\V1\NoteController::class, 'store'])->name('notes.store');
    Route::put('/{id}', [\App\Http\Controllers\Api\V1\NoteController::class, 'update'])->name('notes.update');
    Route::delete('/{id}', [\App\Http\Controllers\Api\V1\NoteController::class, 'destroy'])->name('notes.destroy');
});
