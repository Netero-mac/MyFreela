<?php

namespace NeteroMac\MeuFreela;

use Illuminate\Support\ServiceProvider;

class MeuFreelaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'meu-freela');
    }

    public function register(): void
    {

    }
}