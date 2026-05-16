<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Repositories\Contracts\UserRepositoryInterface::class, \App\Repositories\UserRepository::class);
        $this->app->bind(\App\Repositories\Contracts\TipoProcessoRepositoryInterface::class, \App\Repositories\TipoProcessoRepository::class);
        $this->app->bind(\App\Repositories\Contracts\ProcessoRepositoryInterface::class, \App\Repositories\ProcessoRepository::class);
        $this->app->bind(\App\Repositories\Contracts\MovimentacaoRepositoryInterface::class, \App\Repositories\MovimentacaoRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
