<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProyectoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [ClienteController::class, 'index'])->name('clientes.index');
Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
Route::get('/clientes/{cliente}/proyectos', [ProyectoController::class, 'index'])->name('clientes.proyectos.index');
