<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4 sm:mb-0">
                {{ __('Meus Projetos') }}
            </h2>
            <form method="GET" action="{{ route('projects.index') }}" class="w-full sm:w-1/2 lg:w-1/3">
                <div class="flex">
                    <x-text-input type="search" name="search" class="w-full" placeholder="Buscar por título ou cliente..." :value="request('search')" />
                    <x-primary-button class="ms-2">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                    </x-primary-button>
                </div>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 px-4 sm:px-0">
                 <a href="{{ route('projects.create') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                    Novo Projeto
                </a>
            </div>
            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800">
                    {{ session('success') }}
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($projects as $project)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg flex flex-col">
                        <div class="p-6 flex-grow">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $project->title }}</h3>
                                <div class="flex space-x-2">
                                     <a href="{{ route('projects.edit', $project) }}" class="text-gray-400 hover:text-indigo-500"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg></a>
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Tem certeza?');"> @csrf @method('DELETE') <button type="submit" class="text-gray-400 hover:text-red-500"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg></button>@csrf @method('DELETE')</form>
                                </div>
                            </div>
                            
                            {{-- ======================================================= --}}
                            {{-- INÍCIO DO CÓDIGO DO BADGE DE STATUS (A PARTE QUE FALTA) --}}
                            {{-- ======================================================= --}}
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->status->color() }}">
                                {{ $project->status->value }}
                            </span>
                            {{-- ======================================================= --}}
                            {{-- FIM DO CÓDIGO DO BADGE DE STATUS --}}
                            {{-- ======================================================= --}}

                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-4 mb-4">{{ Str::limit($project->description, 100) }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                             <p class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>Cliente: {{ $project->client->name }}</p>
                            <form action="{{ route('projects.updateStatus', $project) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="flex items-center space-x-2">
                                    <select name="status" class="block w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        @foreach(App\Enums\ProjectStatus::cases() as $status)
                                            <option value="{{ $status->value }}" @selected($project->status === $status)>
                                                {{ $status->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="text-xs text-white bg-indigo-600 hover:bg-indigo-700 rounded-md px-3 py-2">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center text-gray-500">Nenhum projeto encontrado.</div>
                @endforelse
            </div>
            <div class="mt-8">{{ $projects->appends(request()->query())->links() }}</div>
        </div>
    </div>
</x-app-layout>