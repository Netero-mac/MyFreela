<?php

namespace NeteroMac\MeuFreela\Http\Controllers;

use NeteroMac\MeuFreela\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    /**
     * Exibe uma lista de clientes do usuário, com busca e paginação.
     */
    public function index(Request $request)
    {
        $clientsQuery = auth()->user()->clients();

        // Sua lógica de busca está ótima!
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

    /**
     * Mostra o formulário para criar um novo cliente.
     */
    public function create()
    {
        return view('meu-freela::clients.create');
    }

    /**
     * Salva um novo cliente no banco de dados.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clients',
            'phone' => 'nullable|string|max:20',
        ]);

        // Forma mais limpa e segura de criar, já associando o user_id automaticamente.
        auth()->user()->clients()->create($validated);

        return redirect()->route('clients.index')->with('success', 'Cliente criado com sucesso!');
    }

    /**
     * Mostra o formulário para editar um cliente.
     */
    public function edit(Client $client)
    {
        // Padronizando a autorização via Policy.
        $this->authorize('update', $client);
        
        return view('meu-freela::clients.edit', compact('client'));
    }

    /**
     * Atualiza um cliente no banco de dados.
     */
    public function update(Request $request, Client $client)
    {
        $this->authorize('update', $client);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('clients')->ignore($client->id)],
            'phone' => 'nullable|string|max:20',
        ]);
        
        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    /**
     * Exclui um cliente do banco de dados.
     */
    public function destroy(Client $client)
    {
        $this->authorize('delete', $client);

        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Cliente excluído com sucesso!');
    }
}