<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

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

    // Rutas que requieren permisos de edición
    Route::middleware('permissions:edit')->group(function () {
        // Rutas de clientes
        Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
        Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
        
        // Rutas de proyectos
        Route::get('/clientes/{cliente}/proyectos/create', [ProyectoController::class, 'create'])
            ->name('clientes.proyectos.create');
        Route::post('/clientes/{cliente}/proyectos', [ProyectoController::class, 'store'])
            ->name('clientes.proyectos.store');
    });

    // Rutas de solo lectura (accesibles para todos los usuarios autenticados)
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');
    Route::get('/clientes/{cliente}/proyectos', [ProyectoController::class, 'index'])->name('clientes.proyectos.index');
    Route::get('/clientes/{cliente}/proyectos/{proyecto}', [ProyectoController::class, 'show'])->name('clientes.proyectos.show');
    Route::get('/proyectos', [ProyectoController::class, 'todos'])->name('proyectos.todos');
    Route::get('/exportar', [DataController::class, 'exportar'])->name('exportar');

    // Rutas que requieren permisos de edición
    Route::middleware('permissions:edit')->group(function () {
        Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
        Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
        Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
        
        Route::get('/clientes/{cliente}/proyectos/{proyecto}/edit', [ProyectoController::class, 'edit'])
            ->name('clientes.proyectos.edit');
        Route::put('/clientes/{cliente}/proyectos/{proyecto}', [ProyectoController::class, 'update'])
            ->name('clientes.proyectos.update');
        Route::delete('/clientes/{cliente}/proyectos/{proyecto}', [ProyectoController::class, 'destroy'])
            ->name('clientes.proyectos.destroy');
        
        Route::post('/importar', [DataController::class, 'importar'])->name('importar');
    });

    // Rutas que requieren permisos de administrador
    Route::middleware(['auth', 'permissions:admin'])->group(function () {
        Route::get('/register', function () {
            return view('auth.register');
        })->name('register');
        Route::post('/register', [AuthController::class, 'webRegister'])->name('register.post');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/verify-admin-password', [AuthController::class, 'verifyAdminPassword'])
            ->name('verify.admin.password')
            ->withoutMiddleware(['permissions:admin']);
    });

    Route::post('/logout', [AuthController::class, 'webLogout'])->name('logout');

    Route::delete('/proyectos/{proyecto}', [ProyectoController::class, 'destroy'])
        ->name('proyectos.destroy')
        ->middleware(['auth']);

    Route::get('/dashboard/kpis', [DashboardController::class, 'kpis'])->name('dashboard.kpis');
    Route::get('/dashboard/top-clientes', [DashboardController::class, 'topClientesDetalle'])->name('dashboard.top-clientes');
    Route::get('/dashboard/presupuestos', [DashboardController::class, 'presupuestosDetalle'])->name('dashboard.presupuestos');
    Route::get('/dashboard/rendimiento', [DashboardController::class, 'rendimientoDetalle'])->name('dashboard.rendimiento');
});
