<?php

namespace NeteroMac\MeuFreela\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use NeteroMac\MeuFreela\Models\Client;

class ClientController extends Controller
{
    /**
     * Exibe uma lista de clientes do usuário, com busca e paginação.
     */
    public function index(Request $request)
    {
        // Inicia a query a partir do relacionamento para garantir
        // que o usuário só veja os seus próprios clientes.
        $clientsQuery = auth()->user()->clients();

        // Lógica de busca condicional, elegante e eficiente.
        $clientsQuery->when($request->filled('search'), function ($query) use ($request) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm);
            });
        });

        // Traz os resultados mais recentes primeiro e com paginação. Ótimo para a performance!
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

        // Forma mais limpa e segura de criar, já associando o user_id automaticamente. Perfeito!
        auth()->user()->clients()->create($validated);

        return redirect()->route('clients.index')->with('success', 'Cliente criado com sucesso!');
    }

    /**
     * Mostra o formulário para editar um cliente.
     */
    public function edit(Client $client)
    {
        // A autorização via Policy é o padrão ideal do Laravel. Excelente!
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