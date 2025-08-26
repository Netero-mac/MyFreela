<?php

namespace NeteroMac\MeuFreela\Http\Controllers;

use App\Http\Controllers\Controller;
use NeteroMac\MeuFreela\Models\Project;
use NeteroMac\MeuFreela\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\ProjectStatus; 
use App\Enums\InvoiceStatus; 
use Illuminate\Support\Facades\Storage; 

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoicesQuery = auth()->user()->invoices()->with('client', 'project');

        // Adicionado filtro por status da fatura
        $invoicesQuery->when($request->filled('status'), function ($query) use ($request) {
            $query->where('status', $request->status);
        });

        $invoicesQuery->when($request->filled('search'), function ($query) use ($request) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('invoice_number', 'like', $searchTerm)
                    ->orWhereHas('client', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'like', $searchTerm);
                    })
                    ->orWhereHas('project', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'like', $searchTerm);
                    });
            });
        });

        $invoices = $invoicesQuery->latest()->paginate(10);
        
        // Enviando os status para o dropdown de filtro da view
        $statuses = InvoiceStatus::cases();

        return view('meu-freela::invoices.index', compact('invoices', 'statuses'));
    }

     public function store(Request $request, Project $project)
    {
        $request->validate([
            'invoice_pdf' => 'required|mimes:pdf|max:2048', // Valida se é um PDF de até 2MB
        ]);

        if ($request->hasFile('invoice_pdf')) {
            // Gera um nome de arquivo único e armazena o PDF
            $path = $request->file('invoice_pdf')->store('invoices', 'local');

            // Cria o registro no banco de dados
            $project->invoice()->create([
                'user_id' => auth()->id(),
                'client_id' => $project->client_id,
                'status' => \App\Enums\InvoiceStatus::PENDING, // Status inicial
                'file_path' => $path, // Salva o caminho do arquivo
            ]);

            return redirect()->route('invoices.index')->with('success', 'Fatura anexada com sucesso!');
        }

        return back()->with('error', 'Ocorreu um erro ao anexar a fatura.');
    }
    
    // [NOVO] Método para marcar uma fatura como paga
    public function markAsPaid(Invoice $invoice)
    {
        // Verificação manual para garantir que o usuário é o dono da fatura.
        abort_if(auth()->user()->id !== $invoice->user_id, 403, 'This action is unauthorized.');
        
        $invoice->update(['status' => InvoiceStatus::PAID]);

        return back()->with('success', 'Fatura marcada como paga.');
    }

    // [NOVO] Método para cancelar uma fatura
    public function cancel(Invoice $invoice)
    {
        // Verificação manual para garantir que o usuário é o dono da fatura.
        abort_if(auth()->user()->id !== $invoice->user_id, 403, 'This action is unauthorized.');

        // Regra de negócio: não se pode cancelar uma fatura já paga.
        abort_if($invoice->status === InvoiceStatus::PAID, 422, 'Não é possível cancelar uma fatura que já foi paga.');

        $invoice->update(['status' => InvoiceStatus::CANCELED]);

        return back()->with('success', 'Fatura cancelada com sucesso.');
    }

    public function download(Invoice $invoice)
    {
        // 1. Garante que o usuário só pode baixar sua própria fatura
        abort_if($invoice->user_id !== auth()->id(), 403);

        // 2. Verifica se o caminho do arquivo existe no registro do banco
        abort_if(is_null($invoice->file_path), 404, 'Nenhum arquivo anexado a esta fatura.');

        // 3. Verifica se o arquivo físico existe no storage
        if (!Storage::disk('local')->exists($invoice->file_path)) {
            abort(404, 'Arquivo não encontrado no servidor.');
        }

        // 4. Retorna o arquivo para download
        // O nome do arquivo será algo como "fatura-projeto-123.pdf"
        $projectName = Str::slug($invoice->project->title ?? 'projeto');
        $fileName = "fatura-{$projectName}-{$invoice->id}.pdf";

        return Storage::disk('local')->download($invoice->file_path, $fileName);
    }

     public function create(Project $project)
    {
        return view('meu-freela::invoices.create', compact('project'));
    }
}