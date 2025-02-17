<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProyectoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/clientes', [ClienteController::class, 'store']);

// Si quieres crear un grupo de rutas para clientes, puedes hacerlo así:
Route::prefix('clientes')->group(function () {
    Route::post('/', [ClienteController::class, 'store']);
    Route::get('/', [ClienteController::class, 'index']);
    Route::get('/{cliente}', [ClienteController::class, 'show']);
    Route::put('/{cliente}', [ClienteController::class, 'update']);
    Route::delete('/{cliente}', [ClienteController::class, 'destroy']);
});

// Rutas para proyectos
Route::prefix('clientes/{cliente}/proyectos')->group(function () {
    Route::post('/', [ProyectoController::class, 'store']);
    Route::get('/', [ProyectoController::class, 'index']);
    Route::get('/{proyecto}', [ProyectoController::class, 'show']);
    Route::put('/{proyecto}', [ProyectoController::class, 'update']);
    Route::delete('/{proyecto}', [ProyectoController::class, 'destroy']);
});
