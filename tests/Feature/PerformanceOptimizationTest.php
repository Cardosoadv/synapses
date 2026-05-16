<?php

namespace Tests\Feature;

use App\Models\Movimentacao;
use App\Models\Processo;
use App\Models\TipoProcesso;
use App\Models\User;
use App\Repositories\Contracts\ProcessoRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PerformanceOptimizationTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(ProcessoRepositoryInterface::class);
    }

    public function test_find_by_id_eager_loads_movimentacoes_and_users()
    {
        $tipo = TipoProcesso::create(['nome' => 'Teste', 'is_active' => true]);
        $interessado = User::factory()->create();
        $processo = Processo::create([
            'numero' => '00001.000001/2024-01',
            'tipo_processo_id' => $tipo->id,
            'interessado_id' => $interessado->id,
            'assunto' => 'Assunto Teste',
            'nivel_acesso' => 'publico',
            'status' => 'aberto',
            'data_abertura' => now(),
        ]);

        // Create 5 movements with different users
        for ($i = 0; $i < 5; $i++) {
            $user = User::factory()->create();
            Movimentacao::create([
                'processo_id' => $processo->id,
                'user_id' => $user->id,
                'status_anterior' => 'aberto',
                'status_novo' => 'em_analise',
                'observacao' => "Movimento $i",
            ]);
        }

        DB::flushQueryLog();
        DB::enableQueryLog();

        $foundProcesso = $this->repository->findById($processo->id);

        // Trigger loading of relationships
        foreach ($foundProcesso->movimentacoes as $movimentacao) {
            $name = $movimentacao->user->name;
        }

        $queries = DB::getQueryLog();

        // Optimized:
        // 1. Fetch processo (1 query)
        // 2. Fetch tipoProcesso (1 query)
        // 3. Fetch interessado (1 query)
        // 4. Fetch movimentacoes (1 query)
        // 5. Fetch users of movimentacoes (1 query)
        // Total expected: 5 queries

        $this->assertLessThanOrEqual(5, count($queries), "Should have 5 or fewer queries if N+1 is resolved");
    }

    public function test_get_latest_process_number_uses_sargable_query()
    {
        DB::flushQueryLog();
        DB::enableQueryLog();

        $this->repository->getLatestProcessNumber(2024);

        $queries = DB::getQueryLog();
        $lastQuery = end($queries)['query'];

        // Check if whereYear is NOT used
        $this->assertStringNotContainsString('strftime(\'%Y\', "data_abertura")', $lastQuery, "Should NOT use strftime for year in SQLite if using SARGable query");
        $this->assertStringContainsString('"data_abertura" >= ?', $lastQuery);
        $this->assertStringContainsString('"data_abertura" < ?', $lastQuery);
    }
}
