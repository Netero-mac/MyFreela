<?php

namespace NeteroMac\MeuFreela\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface UserInterface
{
    /**
     * Relação com os clientes do usuário.
     */
    public function clients(): HasMany;

    /**
     * Relação com os projetos do usuário.
     */
    public function projects(): HasMany;

    /**
     * Relação com as faturas do usuário.
     */
    public function invoices(): HasMany; // 👈 Adicionado novo método
}