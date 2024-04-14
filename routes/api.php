<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GiphyController;
use App\Http\Controllers\FavoriteController;

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

Route::middleware('logger')
    ->group(function () {
    Route::get('/search-gifs', [GiphyController::class, 'search']);
    Route::get('/find-gif', [GiphyController::class, 'getById']);
    Route::post('/favorite', [FavoriteController::class, 'store']);
});
