<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMotivosVinculosTable extends Migration
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
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
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
        $this->forge->createTable('motivos_vinculos');

        // Seed initial data
        $seeder = \Config\Database::seeder();
        // Since we can't easily run a seeder from here without a file, we can insert raw data or just create the table.
        // We'll leave seeding for a seeder file or manual insertion if requested, but the requirement implies "cadastro previo", so I'll insert a few default values in the migration for convenience or leave it empty? 
        // Better to just create the table now.
    }

    public function down()
    {
        $this->forge->dropTable('motivos_vinculos');
    }
}
