<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4 sm:mb-0">
                {{ __('Meus Clientes') }}
            </h2>
            {{-- Barra de Pesquisa --}}
            <form method="GET" action="{{ route('clients.index') }}" class="w-full sm:w-1/2 lg:w-1/3">
                <div class="flex">
                    <x-text-input type="search" name="search" class="w-full" placeholder="Buscar por nome ou email..." :value="request('search')" />
                    <x-primary-button class="ms-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </x-primary-button>
                </div>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Botão de Novo Cliente (visível em telas menores) --}}
            <div class="mb-6 px-4 sm:px-0">
                <a href="{{ route('clients.create') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                    Novo Cliente
                </a>
            </div>

            @if (session('success'))
            <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800">
                {{ session('success') }}
            </div>
            @endif

            {{-- Grade de Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($clients as $client)
                {{-- Card Individual --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg flex flex-col justify-between">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{-- Cabeçalho do Card --}}
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-bold">{{ $client->name }}</h3>
                            <div class="flex space-x-2">
                                {{-- Botão de Editar --}}
                                <a href="{{ route('clients.edit', $client) }}" class="text-gray-400 hover:text-indigo-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                                </a>
                                {{-- Botão para ACIONAR o modal de exclusão --}}
                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-client-deletion-{{ $client->id }}')" class="text-gray-400 hover:text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Corpo do Card (Corrigido) --}}
                        <div class="space-y-2">
                            <p class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" /><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" /></svg>
                                {{ $client->email }}
                            </p>
                            @if($client->phone)
                            <p class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.06-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" /></svg>
                                {{ $client->phone }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- O Modal de Confirmação para este cliente --}}
                <x-modal name="confirm-client-deletion-{{ $client->id }}" focusable>
                    <form method="post" action="{{ route('clients.destroy', $client) }}" class="p-6">
                        @csrf
                        @method('delete')
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Tem certeza que deseja excluir o cliente "{{ $client->name }}"?
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Uma vez excluído, todos os seus dados, incluindo projetos associados, serão permanentemente apagados.
                        </p>
                        <div class="mt-6 flex justify-end">
                            <x-secondary-button x-on:click="$dispatch('close')">
                                {{ __('Cancelar') }}
                            </x-secondary-button>
                            <x-danger-button class="ms-3">
                                {{ __('Excluir Cliente') }}
                            </x-danger-button>
                        </div>
                    </form>
                </x-modal>

                @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center text-gray-500">
                    Nenhum cliente encontrado.
                </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $clients->appends(request()->query())->links() }}
            </div>

        </div>
    </div>
</x-app-layout>