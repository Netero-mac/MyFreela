<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4 sm:mb-0">
                {{ __('Minhas Faturas') }}
            </h2>
            <form method="GET" action="{{ route('invoices.index') }}" class="w-full sm:w-1/2 lg:w-1/3">
                <div class="flex">
                    <x-text-input type="search" name="search" class="w-full" placeholder="Buscar por nº, projeto ou cliente..." :value="request('search')" />
                    <x-primary-button class="ms-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                    </x-primary-button>
                </div>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800">
                    {{ session('success') }}
                </div>
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nº Fatura</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Projeto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Valor Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vencimento</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($invoices as $invoice)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-white">{{ $invoice->invoice_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $invoice->project->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $invoice->client->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">R$ {{ number_format($invoice->total_amount, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $invoice->due_date->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('invoices.download', $invoice) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">Baixar PDF</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                        Nenhuma fatura encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
             <div class="mt-8">{{ $invoices->appends(request()->query())->links() }}</div>
        </div>
    </div>
</x-app-layout>