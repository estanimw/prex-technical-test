<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\GiphyController;
use App\Http\Controllers\UserController;
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

Route::middleware('logger')
    ->group(function () {
    Route::withoutMiddleware('auth:api')->prefix('user')->group(function () {
        Route::post('/login', [UserController::class, 'login']);
        Route::post('/register', [UserController::class, 'register']);
    });

    Route::middleware('auth:api')->group(function () {
        Route::get('/search-gifs', [GiphyController::class, 'search']);
        Route::get('/find-gif', [GiphyController::class, 'getById']);
        Route::post('/favorite', [FavoriteController::class, 'store']);
    });
});
