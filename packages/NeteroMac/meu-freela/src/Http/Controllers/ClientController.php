<?php

// Namespace correto com "M" maiúsculo
namespace NeteroMac\MeuFreela\Http\Controllers;

// USE STATEMENTS CORRIGIDOS com "M" maiúsculo
use NeteroMac\MeuFreela\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    public function index()
    {
        $clients = auth()->user()->clients()->latest()->paginate(10);
        return view('meu-freela::clients.index', compact('clients'));
    }

    public function create()
    {
        return view('meu-freela::clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clients',
            'phone' => 'nullable|string|max:20',
        ]);
        $request->user()->clients()->create($validated);
        return redirect()->route('clients.index')->with('success', 'Cliente criado com sucesso!');
    }

    public function show(Client $client)
    {
        // Geralmente não usamos o show com resource, mas se precisar, está aqui.
    }

    public function edit(Client $client)
    {
        if ($client->user_id !== auth()->id()) {
            abort(403, 'Acesso Negado');
        }
        return view('meu-freela::clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        if ($client->user_id !== auth()->id()) {
            abort(403, 'Acesso Negado');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('clients')->ignore($client->id)],
            'phone' => 'nullable|string|max:20',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    public function destroy(Client $client)
    {
        if ($client->user_id !== auth()->id()) {
            abort(403, 'Acesso Negado');
        }

        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Cliente excluído com sucesso!');
    }
}