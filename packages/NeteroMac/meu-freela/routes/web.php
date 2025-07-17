<?php

use Illuminate\Support\Facades\Route;
use NeteroMac\MeuFreela\Http\Controllers\ClientController;
use NeteroMac\MeuFreela\Http\Controllers\ProjectController;
use NeteroMac\MeuFreela\Http\Controllers\InvoiceController; // Adicione este import

Route::middleware(['web', 'auth'])->group(function () {
    Route::patch('projects/{project}/status', [ProjectController::class, 'updateStatus'])->name('projects.updateStatus');
    Route::resource('clients', ClientController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('invoices', InvoiceController::class)->only(['index', 'store']);
    Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
});