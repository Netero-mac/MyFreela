<?php

namespace NeteroMac\MeuFreela;

use Illuminate\Support\ServiceProvider;

class MeuFreelaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Carrega as rotas do pacote
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Carrega as migrações do banco de dados do pacote
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Carrega as views do pacote
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'meu-freela');

        // Se no futuro você tiver arquivos de configuração ou assets para publicar,
        // eles também seriam declarados aqui.
    }

    public function register(): void
    {
        // Nada para registrar por enquanto.
    }
}