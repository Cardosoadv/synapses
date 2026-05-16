<?php

namespace App\Providers;

use App\Repositories\Contracts\DocumentoRepositoryInterface;
use App\Repositories\Contracts\MovimentacaoRepositoryInterface;
use App\Repositories\Contracts\ProcessoRepositoryInterface;
use App\Repositories\Contracts\TipoProcessoRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\DocumentoRepository;
use App\Repositories\MovimentacaoRepository;
use App\Repositories\ProcessoRepository;
use App\Repositories\TipoProcessoRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TipoProcessoRepositoryInterface::class, TipoProcessoRepository::class);
        $this->app->bind(ProcessoRepositoryInterface::class, ProcessoRepository::class);
        $this->app->bind(DocumentoRepositoryInterface::class, DocumentoRepository::class);
        $this->app->bind(MovimentacaoRepositoryInterface::class, MovimentacaoRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
