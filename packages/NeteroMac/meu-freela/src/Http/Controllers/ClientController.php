<?php

namespace NeteroMac\MeuFreela\Http\Controllers;

use NeteroMac\MeuFreela\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;


class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clientsQuery = auth()->user()->clients();

        // Adiciona a lógica de busca
        $clientsQuery->when($request->filled('search'), function ($query) use ($request) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm);
            });
        });

        $clients = $clientsQuery->latest()->paginate(10);

        return view('meu-freela::clients.index', compact('clients'));
    }

    public function create()
    {
        return view('meu-freela::clients.create');
    }

    public function store(StoreClientRequest $request)
    {
        $request->user()->clients()->create($request->validated());
        return redirect()->route('clients.index')->with('success', 'Cliente criado com sucesso!');
    }

    public function show(Client $client) {}

    public function edit(Client $client)
    {
        if ($client->user_id !== auth()->id()) {
            abort(403, 'Acesso Negado');
        }
        return view('meu-freela::clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        // A autorização é verificada aqui. Se falhar, um 403 é retornado.
        $this->authorize('update', $client);

        $validated = $request->validate([/*...*/]);
        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    public function destroy(Client $client)
    {
        $this->authorize('delete', $client);

        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Cliente excluído com sucesso!');
    }
}
