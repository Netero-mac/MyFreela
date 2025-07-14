<x-app-layout>
    <x-slot name="header">
        {{-- [MUDANÇA] Adicionada a classe "text-center" para centralizar o título --}}
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('MyFreela') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- [MUDANÇA] Adicionada a classe "text-center" em cada card para centralizar seu conteúdo --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Projetos</h3>
                        <p class="mt-1 text-3xl font-semibold">{{ $totalProjects }}</p>
                    </div>
                </div>

                <div class="bg-green-100 dark:bg-green-800/50 border border-green-200 dark:border-green-700 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 text-green-900 dark:text-green-200 text-center">
                        <h3 class="text-sm font-medium">Faturado (Concluído)</h3>
                        <p class="mt-1 text-3xl font-semibold">R$ {{ $faturado }}</p>
                    </div>
                </div>

                <div class="bg-yellow-100 dark:bg-yellow-800/50 border border-yellow-200 dark:border-yellow-700 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 text-yellow-900 dark:text-yellow-200 text-center">
                        <h3 class="text-sm font-medium">A Receber (Ativos)</h3>
                        <p class="mt-1 text-3xl font-semibold">R$ {{ $aReceber }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Em Andamento</h3>
                        <p class="mt-1 text-3xl font-semibold">{{ $inProgressProjects }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Concluídos</h3>
                        <p class="mt-1 text-3xl font-semibold">{{ $completedProjects }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Clientes</h3>
                        <p class="mt-1 text-3xl font-semibold">{{ $totalClients }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4 text-center">Prazos se Esgotando!</h3>
                    <ul class="space-y-3">
                        @forelse($projetosUrgentes as $projeto)
                        <li class="flex justify-between items-center p-3 bg-red-50 dark:bg-red-800/30 rounded-lg">
                            <span class="font-medium dark:text-gray-200">{{ $projeto->title }}</span>
                            <span class="text-sm font-bold text-red-600 dark:text-red-400">
                                {{ $projeto->deadline->diffForHumans() }}
                            </span>
                        </li>
                        @empty
                        <p class="text-center text-gray-500">Nenhum projeto com prazo para os próximos 15 dias.</p>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4 text-center">Distribuição de Projetos</h3>
                    <div style="height: 300px;">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('statusChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar', // TIPO DE GRÁFICO: de 'pie' para 'bar'
                        data: {
                            labels: @json($chartLabels),
                            datasets: [{
                                label: 'Projetos por Status',
                                data: @json($chartData),
                                backgroundColor: @json($chartColors), // Usa as cores que passamos do backend
                                borderColor: @json($chartColors), // Borda com a mesma cor
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false // Oculta a legenda, pois as labels já estão no eixo X
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true, // Garante que o eixo Y comece no zero
                                    ticks: {
                                        // Garante que os ticks sejam apenas números inteiros
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                });
            </script>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- [MUDANÇA] Adicionada a classe "text-center" para centralizar o título da seção --}}
                    <h3 class="text-lg font-semibold mb-4 text-center">Projetos Recentes</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    {{-- [MUDANÇA] Trocado "text-left" por "text-center" nos cabeçalhos da tabela --}}
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Projeto</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Prazo</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($recentProjects as $project)
                                <tr>
                                    {{-- [MUDANÇA] Adicionada a classe "text-center" nas células da tabela --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-center">{{ $project->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">{{ $project->client->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->status->color() }}">
                                            {{ $project->status->value }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">{{ $project->deadline ? $project->deadline->format('d/m/Y') : 'N/D' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                        Nenhum projeto recente encontrado.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>