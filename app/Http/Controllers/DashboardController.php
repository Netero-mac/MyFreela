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

        $projects = $user->projects()->with('client')->get();

        $totalProjects = $projects->count();
        $faturado = $projects->where('status', ProjectStatus::COMPLETED)->sum('value');
        $aReceber = $projects->whereIn('status', [ProjectStatus::PENDING, ProjectStatus::IN_PROGRESS])->sum('value');

        $statusCounts = $user->projects()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $chartLabels = [];
        $chartData = [];
        $chartColors = [];

        foreach (\App\Enums\ProjectStatus::cases() as $status) {
            $chartLabels[] = $status->value;
            $chartData[] = $statusCounts[$status->value] ?? 0;
            $chartColors[] = $status->getChartColor();
        }

        $recentProjects = $projects->sortByDesc('created_at')->take(5);
        $projetosUrgentes = $projects->where('status', '!=', ProjectStatus::COMPLETED)
            ->where('deadline', '!=', null)
            ->where('deadline', '<=', now()->addDays(15))
            ->sortBy('deadline');

        return view('dashboard', [
            'totalProjects' => $totalProjects,
            'inProgressProjects' => $statusCounts[ProjectStatus::IN_PROGRESS->value] ?? 0,
            'completedProjects' => $statusCounts[ProjectStatus::COMPLETED->value] ?? 0,
            'totalClients' => $user->clients()->count(), 
            'aReceber' => $aReceber,
            'recentProjects' => $recentProjects,
            'projetosUrgentes' => $projetosUrgentes,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'chartColors' => $chartColors,
            'faturado' => $faturado,
        ]);
    }
}
