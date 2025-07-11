<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Meus Clientes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p>A view de clientes do pacote está funcionando!</p>

                    @forelse ($clients as $client)
                        <div class="border-b py-2">
                            {{ $client->name }}
                        </div>
                    @empty
                        <p>Nenhum cliente cadastrado.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>