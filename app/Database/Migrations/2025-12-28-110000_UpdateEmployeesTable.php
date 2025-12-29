<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateEmployeesTable extends Migration
{
    public function up()
    {
        $fields = [
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
        $this->forge->dropColumn('employees', ['photo']);
    }
}
