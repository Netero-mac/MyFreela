<?php

// NAMESPACE CORRIGIDO AQUI
namespace NeteroMac\MeuFreela\Http\Controllers; 

// USE STATEMENTS CORRIGIDOS AQUI
use NeteroMac\MeuFreela\Models\Project;
use NeteroMac\MeuFreela\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    // A lógica interna dos métodos continua a mesma...
    
    public function index()
    {
        $projects = auth()->user()->projects()->with('client')->latest()->paginate(10);
        return view('meu-freela::projects.index', compact('projects'));
    }

    public function create()
    {
        $clients = auth()->user()->clients;
        return view('meu-freela::projects.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'nullable|date',
        ]);
        $request->user()->projects()->create($validated);
        return redirect()->route('projects.index')->with('success', 'Projeto criado com sucesso!');
    }
    

    public function show(Project $project)
    {
        // Futuramente, para ver detalhes de um projeto
    }

    public function edit(Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            abort(403);
        }
        
        $clients = auth()->user()->clients;
        return view('meu-freela::projects.edit', compact('project', 'clients'));
    }

    public function update(Request $request, Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'nullable|date',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Projeto atualizado com sucesso!');
    }

    public function destroy(Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            abort(403);
        }
        
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Projeto excluído com sucesso!');
    }
}