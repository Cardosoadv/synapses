<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateEmployeesTable extends Migration
{
    public function up()
    {
        $fields = [
            'is_lawyer' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
                'null'       => false,
                'description' => 'Indica se é advogado',
            ],
            'rateio_ativo' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
                'null'       => false,
                'description' => 'Participa do rateio de honorários',
            ],
            'oab_numero' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'oab_uf' => [
                'type'       => 'VARCHAR',
                'constraint' => '2',
                'null'       => true,
            ],
            'photo' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'description' => 'Caminho da foto de perfil',
            ],
        ];

        $this->forge->addColumn('employees', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('employees', ['is_lawyer', 'rateio_ativo', 'oab_numero', 'oab_uf', 'photo']);
    }
}
