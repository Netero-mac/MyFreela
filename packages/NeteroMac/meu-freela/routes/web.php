<?php

use Illuminate\Support\Facades\Route;
// CORREÇÃO AQUI: Usando o namespace correto "NeteroMac"
use NeteroMac\MeuFreela\Http\Controllers\ClientController;
use NeteroMac\MeuFreela\Http\Controllers\ProjectController;

// O middleware 'web' e 'auth' garante que o usuário precisa estar logado
// para acessar qualquer uma dessas rotas.
Route::middleware(['web', 'auth'])->group(function () {
    Route::resource('clients', ClientController::class);
    Route::resource('projects', ProjectController::class);
});