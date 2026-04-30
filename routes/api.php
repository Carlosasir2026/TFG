<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AlmacenController;
use App\Http\Controllers\Api\ProductoController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/almacenes', [AlmacenController::class, 'index']);
    Route::post('/almacenes', [AlmacenController::class, 'store']);
    Route::put('/almacenes/{id}', [AlmacenController::class, 'update']);
    Route::delete('/almacenes/{id}', [AlmacenController::class, 'destroy']);

    Route::get('/almacenes/{id}/productos', [ProductoController::class, 'index']);
    Route::post('/productos', [ProductoController::class, 'store']);
    Route::put('/productos/{id}', [ProductoController::class, 'update']);
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy']);
});