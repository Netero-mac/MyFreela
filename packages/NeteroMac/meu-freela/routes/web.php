<?php

use Illuminate\Support\Facades\Route;
use NeteroMac\MeuFreela\Http\Controllers\ClientController;
use NeteroMac\MeuFreela\Http\Controllers\ProjectController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::patch('projects/{project}/status', [ProjectController::class, 'updateStatus'])->name('projects.updateStatus');
    Route::resource('clients', ClientController::class);
    Route::resource('projects', ProjectController::class);
});