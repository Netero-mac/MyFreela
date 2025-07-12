<?php

namespace App\Policies;

use App\Models\User;
use NeteroMac\MeuFreela\Models\Client;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    // ... outros métodos

    public function delete(User $user, Client $client)
    {
        // VAMOS FORÇAR O LARAVEL A NOS MOSTRAR A VERDADE
        dd(
            'QUEM ESTÁ LOGADO (ID):',
            $user->id,
            'QUEM É O DONO DO CLIENTE (USER_ID):',
            $client->user_id,
            'A REGRA (user->id === client->user_id) É VERDADEIRA?:',
            $user->id === $client->user_id
        );

        // O código abaixo não será executado por causa do dd()
        return $user->id === $client->user_id;
    }
}
