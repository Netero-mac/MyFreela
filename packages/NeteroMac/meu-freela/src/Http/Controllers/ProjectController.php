<?php

namespace NeteroMac\MeuFreela\Http\Controllers;

use App\Enums\ProjectStatus; // Importe o Enum que vamos criar
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum as EnumRule; // Importe a regra de validação para Enums
use NeteroMac\MeuFreela\Models\Client;
use NeteroMac\MeuFreela\Models\Project;

class ProjectController extends Controller
{
    /**
     * Exibe uma lista de projetos do usuário, com busca e paginação.
     */
    public function index(Request $request)
    {
        $projectsQuery = auth()->user()->projects()->with('client');

        // Lógica de busca refinada, incluindo o nome do cliente. Ótima adição!
        $projectsQuery->when($request->filled('search'), function ($query) use ($request) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhereHas('client', function ($subQuery) use ($searchTerm) {
                      $subQuery->where('name', 'like', $searchTerm);
                  });
            });
        });

        $projects = $projectsQuery->latest()->paginate(10);
        return view('meu-freela::projects.index', compact('projects'));
    }

    /**
     * Mostra o formulário para criar um novo projeto.
     */
    public function create()
    {
        // Garante que apenas os clientes do usuário logado sejam listados. Perfeito.
        $clients = auth()->user()->clients()->get();
        return view('meu-freela::projects.create', compact('clients'));
    }

    /**
     * Salva um novo projeto no banco de dados.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Excelente validação de segurança! Garante que o client_id pertence ao usuário.
            'client_id' => ['required', Rule::exists('clients', 'id')->where('user_id', auth()->id())],
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'nullable|date',
        ]);

        auth()->user()->projects()->create($validated);

        return redirect()->route('projects.index')->with('success', 'Projeto criado com sucesso!');
    }

    /**
     * Mostra o formulário para editar um projeto.
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        $clients = auth()->user()->clients()->get();
        return view('meu-freela::projects.edit', compact('project', 'clients'));
    }

    /**
     * Atualiza um projeto no banco de dados.
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'client_id' => ['required', Rule::exists('clients', 'id')->where('user_id', auth()->id())],
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'nullable|date',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Projeto atualizado com sucesso!');
    }

    /**
     * Exclui um projeto do banco de dados.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Projeto excluído com sucesso!');
    }

    /**
     * Atualiza o status de um projeto.
     */
    public function updateStatus(Request $request, Project $project)
    {
        $this->authorize('update', $project); // Reutilizar a policy é uma ótima ideia.

        // Validação robusta usando a regra específica para Enums.
        $validated = $request->validate([
            'status' => ['required', new EnumRule(ProjectStatus::class)],
        ]);
        
        // Atualização segura usando o valor validado.
        $project->update($validated);

        return back()->with('success', 'Status do projeto atualizado!');
    }
}