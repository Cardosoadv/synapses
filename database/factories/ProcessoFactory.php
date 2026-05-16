<?php

namespace Database\Factories;

use App\Models\Processo;
use App\Models\TipoProcesso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProcessoFactory extends Factory
{
    protected $model = Processo::class;

    public function definition(): array
    {
        return [
            'numero' => $this->faker->unique()->numerify('#####.######/####-##'),
            'tipo_processo_id' => TipoProcesso::factory(),
            'interessado_id' => User::factory(),
            'assunto' => $this->faker->sentence(),
            'descricao' => $this->faker->paragraph(),
            'nivel_acesso' => $this->faker->randomElement(['publico', 'restrito', 'sigiloso']),
            'status' => $this->faker->randomElement(['aberto', 'em_analise', 'concluido', 'arquivado']),
            'data_abertura' => now(),
        ];
    }
}
