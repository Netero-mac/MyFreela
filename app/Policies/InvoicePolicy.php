<?php

namespace App\Policies;

use App\Models\User;
use NeteroMac\MeuFreela\Models\Invoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicePolicy
{
    use HandlesAuthorization;

    // O usuário pode ver qualquer fatura? (Listagem)
    public function viewAny(User $user): bool
    {
        return true; // Todo usuário logado pode ver suas próprias faturas
    }

    // O usuário pode ver uma fatura específica?
    public function view(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->user_id;
    }

    // O usuário pode criar uma fatura?
    public function create(User $user): bool
    {
        return true; // Todo usuário logado pode criar faturas
    }

    // O usuário pode atualizar uma fatura?
    public function update(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->user_id;
    }

    // O usuário pode deletar uma fatura?
    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->user_id;
    }

    // [IMPORTANTE] Permissão para fazer o download
    public function download(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->user_id;
    }
}