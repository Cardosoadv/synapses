<?php

namespace Tests\Feature\Api;

use App\Models\Processo;
use App\Models\TipoProcesso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProcessoApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = JWTAuth::fromUser($this->user);
    }

    public function test_can_list_processos()
    {
        Processo::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/processos');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_processo()
    {
        $tipo = TipoProcesso::factory()->create();
        $data = [
            'tipo_processo_id' => $tipo->id,
            'assunto' => 'Assunto Teste API',
            'descricao' => 'Descricao Teste API',
            'nivel_acesso' => 'publico',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/processos', $data);

        $response->assertStatus(201)
            ->assertJsonPath('assunto', 'Assunto Teste API');

        $this->assertDatabaseHas('processos', ['assunto' => 'Assunto Teste API']);
    }

    public function test_can_show_processo()
    {
        $processo = Processo::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson("/api/processos/{$processo->id}");

        $response->assertStatus(200)
            ->assertJsonPath('id', $processo->id);
    }

    public function test_can_update_processo_status()
    {
        $processo = Processo::factory()->create(['status' => 'aberto']);
        $data = ['status' => 'em_analise'];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson("/api/processos/{$processo->id}", $data);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'em_analise');

        $this->assertDatabaseHas('processos', [
            'id' => $processo->id,
            'status' => 'em_analise'
        ]);
    }

    public function test_can_delete_processo()
    {
        $processo = Processo::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("/api/processos/{$processo->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted('processos', ['id' => $processo->id]);
    }
}
