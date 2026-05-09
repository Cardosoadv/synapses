<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\TipoProcessoController;
use App\Http\Controllers\Web\ProcessoController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Web
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Middlewares de Sessão
Route::middleware(['auth'])->group(function () {
    Route::resource('usuarios', UserController::class);
    Route::post('usuarios/{usuario}/toggle-status', [UserController::class, 'toggleStatus'])->name('usuarios.toggle-status');

    // Processos e Tipos
    Route::resource('tipos-processos', TipoProcessoController::class)->except(['show']);
    Route::resource('processos', ProcessoController::class);
    Route::patch('processos/{processo}/status', [ProcessoController::class, 'updateStatus'])->name('processos.update-status');
});
