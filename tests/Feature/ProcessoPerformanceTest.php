<?php

namespace Tests\Feature;

use App\Models\Processo;
use App\Models\User;
use App\Models\TipoProcesso;
use App\Models\Movimentacao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProcessoPerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_processo_view_eager_loads_movimentacoes_user()
    {
        $user = User::factory()->create();

        $tipo = TipoProcesso::create([
            'nome' => 'Memorando',
            'descricao' => 'Memorando interno',
            'is_active' => true,
        ]);

        $processo = Processo::create([
            'numero' => '20240001',
            'tipo_processo_id' => $tipo->id,
            'interessado_id' => $user->id,
            'assunto' => 'Teste',
            'descricao' => 'Teste',
            'nivel_acesso' => 'publico',
            'status' => 'aberto',
            'data_abertura' => now(),
        ]);

        // Create several movements
        for ($i = 0; $i < 5; $i++) {
            Movimentacao::create([
                'processo_id' => $processo->id,
                'user_id' => $user->id,
                'status_anterior' => 'aberto',
                'status_novo' => 'em_analise',
                'observacao' => "Movimentacao $i",
            ]);
        }

        DB::flushQueryLog();
        DB::enableQueryLog();

        $response = $this->actingAs($user)->get(route('processos.show', $processo->id));

        $response->assertStatus(200);

        $queries = DB::getQueryLog();

        // Find queries related to users table
        $userQueries = array_filter($queries, function($query) {
            return str_contains($query['query'], '"users"') || str_contains($query['query'], '`users`');
        });

        // Debugging: count queries
        // fwrite(STDERR, "Total user queries: " . count($userQueries) . "\n");
        // foreach($userQueries as $q) { fwrite(STDERR, "Query: " . $q['query'] . "\n"); }

        // If eager loading is NOT working, we expect many queries (one for each movement + auth user + interessado)
        // Without optimization, it should be at least 7 queries (1 auth, 1 interessado, 5 movements)
        // If eager loading is working, we expect fewer queries.
        // 1 for Auth user, 1 for Interessado (already eager loaded by default),
        // 1 for all movement users (newly eager loaded)
        fwrite(STDERR, "Total user queries found: " . count($userQueries) . "\n");
        foreach($userQueries as $q) { fwrite(STDERR, "Query: " . $q['query'] . "\n"); }

        $this->assertLessThan(6, count($userQueries), "N+1 query detected on users table in processo.show");
    }
}
