<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\OrderController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(static function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);

        Route::prefix('orders')->group(static function () {
            Route::get('/',         [OrderController::class, 'getUserOrders']);
            Route::post('create',   [OrderController::class, 'create']);
            Route::patch('/{id}',   [OrderController::class, 'update']);
            Route::delete('/{id}',  [OrderController::class, 'delete']);
        });
    });
    
});
