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

    public function store(Project $project)
    {
        // Verificação manual para garantir que o usuário é o dono do projeto.
        abort_if(auth()->user()->id !== $project->user_id, 403, 'This action is unauthorized.');

        // Verificação 1: O projeto deve estar "Concluído"
        abort_if($project->status !== ProjectStatus::COMPLETED, 422, 'Apenas projetos concluídos podem ser faturados.');

        // Verificação 2: O projeto não pode ter uma fatura existente
        abort_if($project->invoice()->exists(), 422, 'Este projeto já possui uma fatura gerada.');

        $invoice = Invoice::create([
            'project_id' => $project->id,
            'client_id' => $project->client_id,
            'user_id' => $project->user_id,
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'total_amount' => $project->value,
            'due_date' => now()->addDays(15), 
            'status' => InvoiceStatus::PENDING,
        ]);

        return back()->with('success', 'Fatura gerada com sucesso! Nº ' . $invoice->invoice_number);
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
        // Verificação manual para garantir que o usuário é o dono da fatura.
        abort_if(auth()->user()->id !== $invoice->user_id, 403, 'This action is unauthorized.');

        $invoice->load('project', 'client', 'user');
        $pdf = Pdf::loadView('meu-freela::invoices.pdf', compact('invoice'));

        return $pdf->download('fatura-' . $invoice->invoice_number . '.pdf');
    }
}