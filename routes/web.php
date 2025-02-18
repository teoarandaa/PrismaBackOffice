<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::post('/login', [AuthController::class, 'webLogin'])->name('login.post');
});

// Rutas protegidas
Route::middleware('auth')->group(function () {
    // Redirigir la raíz a clientes
    Route::redirect('/', '/clientes');

    // Rutas de clientes
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::get('/clientes/{cliente}/proyectos', [ProyectoController::class, 'index'])->name('clientes.proyectos.index');
    Route::get('/clientes/{cliente}/proyectos/create', [ProyectoController::class, 'create'])->name('clientes.proyectos.create');
    Route::get('/clientes/{cliente}/proyectos/{proyecto}', [ProyectoController::class, 'show'])->name('clientes.proyectos.show');
    Route::get('/clientes/{cliente}/proyectos/{proyecto}/edit', [ProyectoController::class, 'edit'])->name('clientes.proyectos.edit');
    Route::get('/proyectos', [ProyectoController::class, 'todos'])->name('proyectos.todos');

    // Rutas de importación/exportación
    Route::get('/exportar', [DataController::class, 'exportar'])->name('exportar');
    Route::post('/importar', [DataController::class, 'importar'])->name('importar');

    Route::post('/logout', [AuthController::class, 'webLogout'])->name('logout');
    
    // Solo admin puede registrar usuarios
    Route::middleware('admin')->group(function () {
        Route::get('/register', function () {
            return view('auth.register');
        })->name('register');
        
        Route::post('/register', [AuthController::class, 'webRegister'])->name('register.post');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::post('/verify-admin-password', [UserController::class, 'verifyAdminPassword'])->name('verify.admin.password');
    });
});
