<?php

namespace App\Policies;

use App\Models\User;
use App\Models\NeteroMac\MeuFreela\Models\Client;

class ClientPolicy
{
    public function update(User $user, Client $client): bool
    {
        return $user->id === $client->user_id;
    }

    public function delete(User $user, Client $client): bool
    {
        return $user->id === $client->user_id;
    }
}