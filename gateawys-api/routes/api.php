<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\PeripheralController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/gateways', [GatewayController:: class, 'index']);
Route::prefix('/gateway')->group(function(){
    Route::post('/store', [GatewayController::class, 'store']);
    Route::put('/{id}', [GatewayController::class, 'update']);
    Route::delete('/{id}', [GatewayController::class, 'delete']);
});

Route::get('/peripherals', [PeripheralController:: class, 'index']);
Route::prefix('/peripheral')->group(function(){
    Route::post('/store', [PeripheralController::class, 'store']);
    Route::put('/{id}', [PeripheralController::class, 'update']);
    Route::delete('/{id}', [PeripheralController::class, 'delete']);
});
