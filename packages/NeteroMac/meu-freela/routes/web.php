<?php

//packages/NeteroMac/meu-freela/routes/web.php

use Illuminate\Support\Facades\Route;
use NeteroMac\MeuFreela\Http\Controllers\ClientController;
use NeteroMac\MeuFreela\Http\Controllers\ProjectController;
use NeteroMac\MeuFreela\Http\Controllers\InvoiceController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::resource('clients', ClientController::class);
    Route::resource('projects', ProjectController::class);

    // Rota para a listagem de faturas (continua igual)
    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');

    // Rota CORRETA para criar uma fatura, associada a um projeto
    Route::post('projects/{project}/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    
    // Rota para download (continua igual)
    Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
});