<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCpfToEmployees extends Migration
{
    public function up()
    {
        $this->forge->addColumn('employees', [
            'cpf' => [
                'type'       => 'VARCHAR',
                'constraint' => '14',
                'null'       => true,
                'after'      => 'user_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('employees', 'cpf');
    }
}
