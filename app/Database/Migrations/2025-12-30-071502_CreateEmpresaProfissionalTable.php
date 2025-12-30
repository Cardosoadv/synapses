<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmpresaProfissionalTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'empresa_id' => [
                'type'     => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'profissional_id' => [
                'type'     => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'motivo_id' => [
                'type'     => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'comentario' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'data_inicio' => [
                'type' => 'DATE',
            ],
            'data_fim' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('empresa_id', 'empresas', 'id', 'CASCADE', 'CASCADE');
        // Assuming 'professionals' table exists from previous context (ProfissionaisModel mentions 'professionals' table)
        $this->forge->addForeignKey('profissional_id', 'professionals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('motivo_id', 'motivos_vinculos', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('empresa_profissional');
    }

    public function down()
    {
        $this->forge->dropTable('empresa_profissional');
    }
}
