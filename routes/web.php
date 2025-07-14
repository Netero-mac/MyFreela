<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| As rotas de Client e Project agora são carregadas pelo Service Provider
| do nosso pacote em 'packages/SeuNome/MeuFreela'.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Rota atualizada com dados
Route::get('/dashboard', function () {
    $user = auth()->user();

    // Contadores para os cards de estatísticas
    $totalProjects = $user->projects()->count();
    $inProgressProjects = $user->projects()->where('status', \App\Enums\ProjectStatus::IN_PROGRESS)->count();
    $completedProjects = $user->projects()->where('status', \App\Enums\ProjectStatus::COMPLETED)->count();
    $totalClients = $user->clients()->count();
    $faturado = $user->projects()->where('status', \App\Enums\ProjectStatus::COMPLETED)->sum('value');
    $aReceber = $user->projects()->whereIn('status', [\App\Enums\ProjectStatus::PENDING, \App\Enums\ProjectStatus::IN_PROGRESS])->sum('value');
    $projetosUrgentes = $user->projects()->where('status', '!=', \App\Enums\ProjectStatus::COMPLETED)->where('deadline', '!=', null)->where('deadline', '<=', now()->addDays(15))->orderBy('deadline', 'asc')->get();
    $statusCounts = $user->projects()->select('status', \DB::raw('count(*) as total'))->groupBy('status')->pluck('total', 'status');
    $recentProjects = $user->projects()->with('client')->latest()->take(5)->get();
    $chartLabels = $statusCounts->keys();
    $chartData = $statusCounts->values();

    return view('dashboard', [
        'totalProjects' => $totalProjects,
        'inProgressProjects' => $inProgressProjects,
        'completedProjects' => $completedProjects,
        'totalClients' => $totalClients,
        'recentProjects' => $recentProjects,
        'projetosUrgentes' => $projetosUrgentes,
        'chartLabels' => $chartLabels,
        'chartData' => $chartData,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Apenas as rotas de perfil permanecem aqui
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
