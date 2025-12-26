<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration para a tabela de Funcionários.
 * * Esta tabela estende a funcionalidade de usuários do Shield,
 * armazenando dados específicos da autarquia.
 */
class CreateEmployeesTable extends Migration
{
    public function up()
    {
        // Definição dos campos da tabela
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'description' => 'FK para a tabela users do Shield',
            ],
            'registration_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'unique'     => true,
                'description' => 'Matrícula funcional do servidor',
            ],
            'position' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
                'description' => 'Cargo ocupado',
            ],
            'department' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'description' => 'Lotação / Departamento',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive', 'on_leave'],
                'default'    => 'active',
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
        
        // Chave estrangeira ligando ao Shield (tabela 'users')
        // ON DELETE CASCADE garante que se o user for removido, o perfil também será.
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('employees');
    }

    public function down()
    {
        $this->forge->dropTable('employees');
    }
}