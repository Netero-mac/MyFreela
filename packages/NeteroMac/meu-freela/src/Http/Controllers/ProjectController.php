<?php

namespace NeteroMac\MeuFreela\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum as EnumRule;
use NeteroMac\MeuFreela\Models\Client;
use NeteroMac\MeuFreela\Models\Project;

class ProjectController extends Controller
{
    /**
     * Exibe uma lista de projetos do usuário.
     */
    public function index(Request $request)
    {
        $projectsQuery = auth()->user()->projects()->with('client');

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
        $clients = auth()->user()->clients()->get();
        return view('meu-freela::projects.create', compact('clients'));
    }

    /**
     * Salva um novo projeto no banco de dados.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => ['required', Rule::exists('clients', 'id')->where('user_id', auth()->id())],
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'nullable|date',
            'value' => 'nullable|numeric|min:0',
        ]);

        auth()->user()->projects()->create($validated);

        return redirect()->route('projects.index')->with('success', 'Projeto criado com sucesso!');
    }

    /**
     * Mostra o formulário para editar um projeto.
     */
    public function edit(Project $project)
    {
        // Verificação manual de autorização
        abort_if(auth()->user()->id !== $project->user_id, 403, 'This action is unauthorized.');

        $clients = auth()->user()->clients()->get();
        return view('meu-freela::projects.edit', compact('project', 'clients'));
    }

    /**
     * Atualiza um projeto no banco de dados.
     */
    public function update(Request $request, Project $project)
    {
        // Verificação manual de autorização
        abort_if(auth()->user()->id !== $project->user_id, 403, 'This action is unauthorized.');

        $validated = $request->validate([
            'client_id' => ['required', Rule::exists('clients', 'id')->where('user_id', auth()->id())],
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'nullable|date',
            'value' => 'nullable|numeric|min:0',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Projeto atualizado com sucesso!');
    }

    /**
     * Exclui um projeto do banco de dados.
     */
    public function destroy(Project $project)
    {
        // Verificação manual de autorização
        abort_if(auth()->user()->id !== $project->user_id, 403, 'This action is unauthorized.');

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Projeto excluído com sucesso!');
    }

    /**
     * Atualiza o status de um projeto.
     */
    public function updateStatus(Request $request, Project $project)
    {
        // Verificação manual de autorização
        abort_if(auth()->user()->id !== $project->user_id, 403, 'This action is unauthorized.');

        $validated = $request->validate([
            'status' => ['required', new EnumRule(ProjectStatus::class)],
        ]);
        
        $project->update($validated);

        return back()->with('success', 'Status do projeto atualizado!');
    }
}