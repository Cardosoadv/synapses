<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MotivosVinculosSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nome' => 'Contrato de Trabalho (CLT)', 'created_at' => date('Y-m-d H:i:s')],
            ['nome' => 'Prestação de Serviço (PJ)', 'created_at' => date('Y-m-d H:i:s')],
            ['nome' => 'Estágio', 'created_at' => date('Y-m-d H:i:s')],
            ['nome' => 'Sociedade', 'created_at' => date('Y-m-d H:i:s')],
            ['nome' => 'Parceria', 'created_at' => date('Y-m-d H:i:s')],
            ['nome' => 'Temporário', 'created_at' => date('Y-m-d H:i:s')],
        ];

        // Using query builder to insert
        $this->db->table('motivos_vinculos')->ignore(true)->insertBatch($data);
    }
}
