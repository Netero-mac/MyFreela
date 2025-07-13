<?php

namespace App\Policies;

use App\Models\User;
use NeteroMac\MeuFreela\Models\Client;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    /**
     * Esta linha é a mais importante. Ela "ativa" a policy para o Laravel.
     */
    use HandlesAuthorization;

    /**
     * Determina se o usuário pode ver a lista de clientes.
     */
    public function viewAny(User $user): bool
    {
        // Qualquer usuário logado pode ver sua própria lista.
        return true;
    }

    /**
     * Determina se o usuário pode ver um cliente específico.
     */
    public function view(User $user, Client $client): bool
    {
        // Apenas o dono do cliente pode vê-lo.
        return $user->id === $client->user_id;
    }

    /**
     * Determina se o usuário pode criar clientes.
     */
    public function create(User $user): bool
    {
        // Qualquer usuário logado pode criar um cliente.
        return true;
    }

    /**
     * Determina se o usuário pode atualizar o cliente.
     */
    public function update(User $user, Client $client): bool
    {
        // Apenas o dono do cliente pode atualizá-lo.
        return $user->id === $client->user_id;
    }

    /**
     * Determina se o usuário pode excluir o cliente.
     */
    public function delete(User $user, Client $client): bool
    {
        // Apenas o dono do cliente pode excluí-lo.
        // O seu dd() pode ser colocado aqui para teste, e agora ele será executado.
        return $user->id === $client->user_id;
    }
}