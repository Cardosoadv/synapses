<?php

namespace Database\Factories;

use App\Models\TipoProcesso;
use Illuminate\Database\Eloquent\Factories\Factory;

class TipoProcessoFactory extends Factory
{
    protected $model = TipoProcesso::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->words(3, true),
            'descricao' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
}
