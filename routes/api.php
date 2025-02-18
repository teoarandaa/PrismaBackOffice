<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProyectoController;
use Illuminate\Support\Facades\Route;

// Rutas públicas de autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Rutas de clientes
    Route::apiResource('clientes', ClienteController::class);
    
    // Rutas de proyectos
    Route::prefix('clientes/{cliente}/proyectos')->group(function () {
        Route::get('/', [ProyectoController::class, 'index']);
        Route::post('/', [ProyectoController::class, 'store']);
        Route::get('/{proyecto}', [ProyectoController::class, 'show']);
        Route::put('/{proyecto}', [ProyectoController::class, 'update']);
        Route::delete('/{proyecto}', [ProyectoController::class, 'destroy']);
    });
});
