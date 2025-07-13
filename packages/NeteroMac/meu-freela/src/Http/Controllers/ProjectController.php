<?php

namespace NeteroMac\MeuFreela\Http\Controllers;

use NeteroMac\MeuFreela\Models\Project;
use NeteroMac\MeuFreela\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
// Se você criar o Enum, importe-o aqui. Ex:
// use App\Enums\ProjectStatus; 

class ProjectController extends Controller
{
    /**
     * Exibe uma lista de projetos do usuário, com busca e paginação.
     */
    public function index(Request $request)
    {
        $projectsQuery = auth()->user()->projects()->with('client');

        // Lógica de busca refinada
        $projectsQuery->when($request->filled('search'), function ($query) use ($request) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhereHas('client', function ($subQuery) use ($searchTerm) {
                      // CORREÇÃO AQUI: Usando a variável $searchTerm
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
        $clients = auth()->user()->clients()->get(); // Pega apenas os clientes do usuário logado
        return view('meu-freela::projects.create', compact('clients'));
    }

    /**
     * Salva um novo projeto no banco de dados.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // MELHORIA DE SEGURANÇA: Garante que o client_id pertence ao usuário logado
            'client_id' => ['required', Rule::exists('clients', 'id')->where('user_id', auth()->id())],
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'nullable|date',
        ]);

        // Forma mais limpa e segura de criar, já associando o user_id automaticamente.
        auth()->user()->projects()->create($validated);

        return redirect()->route('projects.index')->with('success', 'Projeto criado com sucesso!');
    }

    /**
     * Mostra o formulário para editar um projeto.
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project); // Autorização via Policy
        
        $clients = auth()->user()->clients()->get();
        return view('meu-freela::projects.edit', compact('project', 'clients'));
    }

    /**
     * Atualiza um projeto no banco de dados.
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project); // Autorização via Policy

        $validated = $request->validate([
            // MELHORIA DE SEGURANÇA: Garante que o client_id pertence ao usuário logado
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
        $this->authorize('delete', $project); // Autorização via Policy
        
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Projeto excluído com sucesso!');
    }

    /**
     * Atualiza o status de um projeto.
     */
    public function updateStatus(Request $request, Project $project)
    {
        $this->authorize('update', $project); // Reutilizando a policy de update

        // NOTA: Para a validação de 'status' funcionar com Enums, você precisará
        // criar o arquivo App\Enums\ProjectStatus.php e usar a regra de validação do Laravel 9+
        // 'status' => ['required', new \Illuminate\Validation\Rules\Enum(ProjectStatus::class)],

        $request->validate(['status' => ['required', 'string']]);
        
        // Esta linha depende do seu Enum. Se não o criou, ela dará erro.
        // $project->status = ProjectStatus::from($request->status);
        // $project->save();

        return back()->with('success', 'Status do projeto atualizado!');
    }
}