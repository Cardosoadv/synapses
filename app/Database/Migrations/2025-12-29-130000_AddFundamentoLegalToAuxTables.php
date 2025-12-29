<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFundamentoLegalToAuxTables extends Migration
{
    public function up()
    {
        $fields = [
            'fundamento_legal' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'nome'
            ],
        ];

        // Add to profissoes
        $this->forge->addColumn('profissoes', $fields);

        // Add to categorias_profissionais
        $this->forge->addColumn('categorias_profissionais', $fields);

        // Add to atribuicoes
        $this->forge->addColumn('atribuicoes', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('profissoes', 'fundamento_legal');
        $this->forge->dropColumn('categorias_profissionais', 'fundamento_legal');
        $this->forge->dropColumn('atribuicoes', 'fundamento_legal');
    }
}
