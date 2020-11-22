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

Route::prefix('currencies')->group(function () {
    Route::post('/add', [\App\Http\Controllers\CurrencyController::class, 'create']);
});

Route::prefix('collections')->group(function () {
    Route::post('/add', [\App\Http\Controllers\CollectionController::class, 'create']);
    Route::get('/{id}/rates', [\App\Http\Controllers\CollectionController::class, 'getExchangeRates']);
    Route::post('/{id}/comment', [\App\Http\Controllers\CollectionController::class, 'addComment']);
});
