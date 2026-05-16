<?php

namespace Tests\Feature;

use App\Models\Processo;
use App\Models\TipoProcesso;
use App\Models\User;
use App\Services\ProcessoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessoMovementTest extends TestCase
{
    use RefreshDatabase;

    protected $processoService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->processoService = app(ProcessoService::class);
    }

    public function test_creating_process_records_initial_movement()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $tipo = TipoProcesso::create([
            'nome' => 'Teste',
            'is_active' => true
        ]);

        $processo = $this->processoService->create([
            'tipo_processo_id' => $tipo->id,
            'assunto' => 'Assunto Teste',
            'interessado_id' => $user->id,
            'nivel_acesso' => 'publico'
        ]);

        $this->assertDatabaseHas('movimentacoes', [
            'processo_id' => $processo->id,
            'status_novo' => 'aberto',
            'user_id' => $user->id
        ]);
    }

    public function test_updating_status_records_movement()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $tipo = TipoProcesso::create([
            'nome' => 'Teste',
            'is_active' => true
        ]);

        $processo = $this->processoService->create([
            'tipo_processo_id' => $tipo->id,
            'assunto' => 'Assunto Teste',
            'interessado_id' => $user->id,
            'nivel_acesso' => 'publico'
        ]);

        $this->processoService->update($processo->id, ['status' => 'em_analise']);

        $this->assertDatabaseHas('movimentacoes', [
            'processo_id' => $processo->id,
            'status_anterior' => 'aberto',
            'status_novo' => 'em_analise',
            'user_id' => $user->id
        ]);
    }
}
