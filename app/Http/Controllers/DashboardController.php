<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        // Otimização: Busca projetos e clientes uma vez
        $projects = $user->projects()->with('client')->get();
        $clients = $user->clients()->get();

        // Cálculos a partir da coleção, sem novas queries
        $totalProjects = $projects->count();
        $inProgressProjects = $projects->where('status', ProjectStatus::IN_PROGRESS)->count();
        $completedProjects = $projects->where('status', ProjectStatus::COMPLETED)->count();
        $faturado = $projects->where('status', ProjectStatus::COMPLETED)->sum('value');
        $aReceber = $projects->whereIn('status', [ProjectStatus::PENDING, ProjectStatus::IN_PROGRESS])->sum('value');
        $chartLabels = [];
        $chartData = [];
        $chartColors = [];

        // Consulta otimizada para o gráfico
        $statusCounts = $projects->groupBy('status')->map->count();
        foreach (\App\Enums\ProjectStatus::cases() as $status) {
            $chartLabels[] = $status->value; // 'Pendente', 'Em Andamento', etc.
            // Se não houver projetos com esse status, usa 0
            $chartData[] = $statusCounts[$status->value] ?? 0;
            // Pega a cor definida no Enum
            $chartColors[] = $status->getChartColor();
        }

        // Outras lógicas...
        $recentProjects = $user->projects()->with('client')->latest()->take(5)->get();
        $projetosUrgentes = $projects->where('status', '!=', ProjectStatus::COMPLETED)
            ->where('deadline', '!=', null)
            ->where('deadline', '<=', now()->addDays(15))
            ->sortBy('deadline');

        return view('dashboard', [
            'totalProjects' => $totalProjects,
            'inProgressProjects' => $inProgressProjects,
            'completedProjects' => $completedProjects,
            'totalClients' => $clients->count(),
            'faturado' => $faturado,
            'aReceber' => $aReceber,
            'recentProjects' => $recentProjects,
            'projetosUrgentes' => $projetosUrgentes,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'chartColors' => $chartColors,
        ]);
    }
}
