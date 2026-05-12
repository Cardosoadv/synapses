<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TipoProcesso;
use App\Models\Processo;
use App\Models\Movimentacao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovimentacaoTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $tipo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->tipo = TipoProcesso::create([
            'nome' => 'Teste Tipo',
            'prefixo' => 'TST',
            'is_active' => true
        ]);
    }

    public function test_creating_process_records_initial_movement()
    {
        $this->actingAs($this->user);

        $processData = [
            'tipo_processo_id' => $this->tipo->id,
            'assunto' => 'Assunto Teste',
            'nivel_acesso' => 'publico',
        ];

        $response = $this->post(route('processos.store'), $processData);
        $response->assertRedirect();

        $processo = Processo::first();
        $this->assertNotNull($processo);

        $this->assertDatabaseHas('movimentacoes', [
            'processo_id' => $processo->id,
            'status_atual' => 'aberto',
            'descricao' => 'Abertura do processo'
        ]);
    }

    public function test_updating_status_records_movement()
    {
        $this->actingAs($this->user);

        $processo = Processo::create([
            'numero' => '00001.000001/2026-01',
            'tipo_processo_id' => $this->tipo->id,
            'assunto' => 'Teste Status',
            'status' => 'aberto',
            'data_abertura' => now(),
        ]);

        $response = $this->patch(route('processos.update-status', $processo->id), [
            'status' => 'em_analise'
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('movimentacoes', [
            'processo_id' => $processo->id,
            'status_anterior' => 'aberto',
            'status_atual' => 'em_analise',
        ]);
    }
}
