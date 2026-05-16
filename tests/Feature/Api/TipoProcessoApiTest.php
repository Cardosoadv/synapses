<?php

namespace Tests\Feature\Api;

use App\Models\TipoProcesso;
use App\Models\Processo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class TipoProcessoApiTest extends TestCase
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

    public function test_can_list_tipos_processos()
    {
        TipoProcesso::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/tipos-processos');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_tipo_processo()
    {
        $data = [
            'nome' => 'Novo Tipo API',
            'descricao' => 'Descricao Novo Tipo API',
            'is_active' => true,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/tipos-processos', $data);

        $response->assertStatus(201)
            ->assertJsonPath('nome', 'Novo Tipo API');

        $this->assertDatabaseHas('tipos_processos', ['nome' => 'Novo Tipo API']);
    }

    public function test_can_show_tipo_processo()
    {
        $tipo = TipoProcesso::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson("/api/tipos-processos/{$tipo->id}");

        $response->assertStatus(200)
            ->assertJsonPath('id', $tipo->id);
    }

    public function test_can_update_tipo_processo()
    {
        $tipo = TipoProcesso::factory()->create(['nome' => 'Tipo Original']);
        $data = [
            'nome' => 'Tipo Atualizado',
            'is_active' => true
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson("/api/tipos-processos/{$tipo->id}", $data);

        $response->assertStatus(200)
            ->assertJsonPath('nome', 'Tipo Atualizado');

        $this->assertDatabaseHas('tipos_processos', [
            'id' => $tipo->id,
            'nome' => 'Tipo Atualizado'
        ]);
    }

    public function test_can_soft_delete_tipo_processo()
    {
        $tipo = TipoProcesso::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("/api/tipos-processos/{$tipo->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted('tipos_processos', ['id' => $tipo->id]);
    }
}
