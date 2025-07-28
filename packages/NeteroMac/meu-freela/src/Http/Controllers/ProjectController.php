<?php

namespace NeteroMac\MeuFreela\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum as EnumRule;
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
        // Verificação manual de autorização mantida.
        abort_if(auth()->user()->id !== $project->user_id, 403, 'This action is unauthorized.');

        $clients = auth()->user()->clients()->get();
        return view('meu-freela::projects.edit', compact('project', 'clients'));
    }

    /**
     * [CORRIGIDO] Atualiza um projeto no banco de dados, incluindo a lógica de status.
     */
    public function update(Request $request, Project $project)
    {
        // 1. Verificação manual de autorização mantida.
        abort_if(auth()->user()->id !== $project->user_id, 403, 'This action is unauthorized.');

        // 2. Validação dos dados da requisição.
        $validated = $request->validate([
            // As regras 'sometimes' garantem que validamos apenas os campos enviados.
            'client_id' => ['sometimes', 'required', Rule::exists('clients', 'id')->where('user_id', auth()->id())],
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'deadline' => 'sometimes|nullable|date',
            'value' => 'sometimes|nullable|numeric|min:0',
            'status' => ['sometimes', 'required', new EnumRule(ProjectStatus::class)],
        ]);
        
        // 3. [NOVO] Lógica da máquina de estados para a transição de status.
        if (isset($validated['status'])) {
            $newStatus = ProjectStatus::tryFrom($validated['status']);

            // Verifica se a transição do estado atual para o novo é permitida.
            if (!$newStatus || !$project->status->canTransitionTo($newStatus)) {
                // Se a transição for inválida, retorna com uma mensagem de erro clara.
                return back()->with('error', 'A transição de status de "' . $project->status->value . '" para "' . $validated['status'] . '" não é permitida.');
            }
        }
        
        // 4. Se todas as validações passaram, atualiza o projeto.
        $project->update($validated);

        // 5. Redireciona de volta para a página anterior com sucesso.
        return back()->with('success', 'Projeto atualizado com sucesso!');
    }

    /**
     * Exclui um projeto do banco de dados.
     */
    public function destroy(Project $project)
    {
        // Verificação manual de autorização mantida.
        abort_if(auth()->user()->id !== $project->user_id, 403, 'This action is unauthorized.');

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Projeto excluído com sucesso!');
    }

    /**
     * [REMOVIDO] O método 'updateStatus' não é mais necessário.
     * A sua funcionalidade foi integrada ao método 'update'.
     */
}