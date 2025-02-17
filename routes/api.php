<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

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
