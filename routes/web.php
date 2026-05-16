<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\TipoProcessoController;
use App\Http\Controllers\Web\ProcessoController;
use App\Http\Controllers\Web\DocumentoController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Web
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Middlewares de Sessão
Route::middleware(['auth'])->group(function () {
    Route::middleware(['admin'])->group(function () {
        Route::resource('usuarios', UserController::class);
        Route::post('usuarios/{usuario}/toggle-status', [UserController::class, 'toggleStatus'])->name('usuarios.toggle-status');
    });

    // Processos e Tipos
    Route::resource('tipos-processos', TipoProcessoController::class)->except(['show']);
    Route::resource('processos', ProcessoController::class);
    Route::patch('processos/{processo}/status', [ProcessoController::class, 'updateStatus'])->name('processos.update-status');

    // Documentos
    Route::get('processos/{processo}/documentos/create', [DocumentoController::class, 'create'])->name('documentos.create');
    Route::post('processos/{processo}/documentos', [DocumentoController::class, 'store'])->name('documentos.store');
    
    // As rotas de documento agora usam UUID
    Route::get('documentos/{uuid}/viewer', [DocumentoController::class, 'viewer'])->name('documentos.viewer');
    Route::get('documentos/{uuid}/view', [DocumentoController::class, 'view'])->name('documentos.view');
    Route::get('documentos/{uuid}/download', [DocumentoController::class, 'download'])->name('documentos.download');
    Route::delete('documentos/{uuid}', [DocumentoController::class, 'destroy'])->name('documentos.destroy');
});
