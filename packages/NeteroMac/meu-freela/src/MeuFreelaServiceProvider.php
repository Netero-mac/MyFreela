<?php

namespace NeteroMac\MeuFreela;

use Illuminate\Support\ServiceProvider;

class MeuFreelaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Caminho corrigido para carregar as rotas
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Carrega as views do pacote com o namespace 'meu-freela'
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'meu-freela');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Nada para registrar por enquanto.
    }
}