<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth Routes
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
    'as' => 'auth.'
], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::get('profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Todos Routes
Route::group([
    'middleware' => 'api',
    'prefix' => 'todos',
    'as' => 'todos.'
], function () {
    Route::get('/', [TodoController::class, 'index'])->name('index');
    Route::get('/{id}', [TodoController::class, 'show'])->name('show');
    Route::post('/', [TodoController::class, 'store'])->name('store');
    Route::post('/{id}', [TodoController::class, 'update'])->name('update');
    Route::put('/{id}/status', [TodoController::class, 'updateStatus'])->name('updateStatus');
    Route::delete('/{id}', [TodoController::class, 'destroy'])->name('destroy');
});
