<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\CitaController;

// Rutas públicas
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware(['api.auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // CRUD
    Route::resource('administradores', AdministradorController::class);
    Route::resource('doctores', DoctorController::class);
    Route::resource('pacientes', PacienteController::class);
    Route::resource('citas', CitaController::class);
    
    // Estado de cita
    Route::patch('citas/{id}/estado', [CitaController::class, 'cambiarEstado'])->name('citas.cambiarEstado');
});