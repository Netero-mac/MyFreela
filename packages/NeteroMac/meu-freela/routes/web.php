<?php

use Illuminate\Support\Facades\Route;
use NeteroMac\MeuFreela\Http\Controllers\ClientController;
use NeteroMac\MeuFreela\Http\Controllers\ProjectController;
use NeteroMac\MeuFreela\Http\Controllers\InvoiceController;

Route::middleware(['web', 'auth'])->group(function () {
    // Mantemos as rotas que já funcionam
    Route::resource('clients', ClientController::class);
    Route::resource('projects', ProjectController::class);
    
    // [CORREÇÃO] Ajuste das rotas de Invoice
    
    // 1. Rota para a listagem (index) de faturas
    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');

    // 2. Rota CORRETA para CRIAR (store) uma fatura, associada a um projeto
    Route::post('projects/{project}/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    
    // 3. Rota para download (continua igual)
    Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');

    Route::get('/projects/{project}/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');

});