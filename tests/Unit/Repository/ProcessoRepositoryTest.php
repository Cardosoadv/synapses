<?php

namespace Tests\Unit\Repository;

use App\Models\Processo;
use App\Models\TipoProcesso;
use App\Models\User;
use App\Repositories\ProcessoRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class ProcessoRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProcessoRepository(new Processo());
    }

    public function test_get_latest_process_number_returns_correct_number_within_year()
    {
        $year = 2025;

        $tipo = TipoProcesso::create([
            'nome' => 'Teste',
            'prefixo' => 'TST',
            'is_active' => true
        ]);

        Processo::create([
            'numero' => '00001.000001/2025-01',
            'tipo_processo_id' => $tipo->id,
            'assunto' => 'Teste 1',
            'data_abertura' => '2025-01-01 10:00:00'
        ]);

        Processo::create([
            'numero' => '00001.000002/2025-01',
            'tipo_processo_id' => $tipo->id,
            'assunto' => 'Teste 2',
            'data_abertura' => '2025-12-31 23:59:59'
        ]);

        // This one should be ignored as it is from a different year
        Processo::create([
            'numero' => '00001.000003/2026-01',
            'tipo_processo_id' => $tipo->id,
            'assunto' => 'Teste 3',
            'data_abertura' => '2026-01-01 00:00:00'
        ]);

        $latest = $this->repository->getLatestProcessNumber(2025);

        $this->assertEquals('00001.000002/2025-01', $latest);
    }

    public function test_get_latest_process_number_returns_null_if_no_process_in_year()
    {
        $tipo = TipoProcesso::create([
            'nome' => 'Teste',
            'prefixo' => 'TST',
            'is_active' => true
        ]);

        Processo::create([
            'numero' => '00001.000001/2024-01',
            'tipo_processo_id' => $tipo->id,
            'assunto' => 'Teste Old',
            'data_abertura' => '2024-12-31 23:59:59'
        ]);

        $latest = $this->repository->getLatestProcessNumber(2025);

        $this->assertNull($latest);
    }
}
