<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-a">
    <title>Fatura {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: sans-serif; line-height: 1.5; }
        .container { width: 100%; margin: 0 auto; padding: 20px; }
        .header, .footer { text-align: center; }
        .details, .items { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .details th, .details td, .items th, .items td { border: 1px solid #ddd; padding: 8px; }
        .details th { text-align: left; background-color: #f2f2f2; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Fatura</h1>
            <p><strong>De:</strong> {{ $invoice->user->name }} ({{ $invoice->user->email }})</p>
        </div>

        <table class="details">
            <tr>
                <th style="width: 50%;">Para</th>
                <th style="width: 50%;">Detalhes da Fatura</th>
            </tr>
            <tr>
                <td>
                    <strong>{{ $invoice->client->name }}</strong><br>
                    {{ $invoice->client->email }}
                </td>
                <td>
                    <strong>Nº da Fatura:</strong> {{ $invoice->invoice_number }}<br>
                    <strong>Data de Emissão:</strong> {{ $invoice->created_at->format('d/m/Y') }}<br>
                    <strong>Data de Vencimento:</strong> {{ $invoice->due_date->format('d/m/Y') }}
                </td>
            </tr>
        </table>

        <table class="items">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th class="text-right">Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Projeto:</strong> {{ $invoice->project->title }}<br>
                        <small>{{ Str::limit($invoice->project->description, 150) }}</small>
                    </td>
                    <td class="text-right">R$ {{ number_format($invoice->total_amount, 2, ',', '.') }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-right">Total:</th>
                    <th class="text-right">R$ {{ number_format($invoice->total_amount, 2, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <div class="footer" style="margin-top: 40px;">
            <p>Obrigado pelo seu negócio!</p>
        </div>
    </div>
</body>
</html>