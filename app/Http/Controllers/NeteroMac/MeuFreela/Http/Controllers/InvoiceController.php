<?php

namespace NeteroMac\MeuFreela\Http\Controllers;

use App\Http\Controllers\Controller;
use NeteroMac\MeuFreela\Models\Project;
use NeteroMac\MeuFreela\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Exibe uma lista de faturas do usuário.
     */
    public function index(Request $request)
    {
        $invoicesQuery = auth()->user()->invoices()->with('client', 'project');

        $invoicesQuery->when($request->filled('search'), function ($query) use ($request) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('invoice_number', 'like', $searchTerm)
                    ->orWhereHas('client', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'like', $searchTerm);
                    })
                    ->orWhereHas('project', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('title', 'like', $searchTerm);
                    });
            });
        });

        $invoices = $invoicesQuery->latest()->paginate(10);

        return view('meu-freela::invoices.index', compact('invoices'));
    }


    /**
     * Cria uma nova fatura para um projeto.
     */
    public function store(Project $project)
    {
        // Verificação de autorização
        abort_if(auth()->user()->id !== $project->user_id, 403);

        $invoice = Invoice::create([
            'project_id' => $project->id,
            'client_id' => $project->client_id,
            'user_id' => $project->user_id,
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'total_amount' => $project->value,
            'due_date' => now()->addDays(15), // Vencimento em 15 dias, por exemplo
            'status' => 'pending',
        ]);

        return back()->with('success', 'Fatura gerada com sucesso! Nº ' . $invoice->invoice_number);
    }

    /**
     * Gera e baixa o PDF de uma fatura.
     */
    public function download(Invoice $invoice)
    {
        // Verificação de autorização
        abort_if(auth()->user()->id !== $invoice->user_id, 403);

        // Carrega os relacionamentos para usar na view
        $invoice->load('project', 'client', 'user');

        $pdf = Pdf::loadView('meu-freela::invoices.pdf', compact('invoice'));

        return $pdf->download('fatura-' . $invoice->invoice_number . '.pdf');
    }
}
