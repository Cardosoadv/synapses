<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration para o Módulo de Registo (RF001).
 * * Cria as tabelas de Profissionais (PF) e Empresas (PJ),
 * incluindo suporte para números de processo do SEI! e status de registo.
 */
class CreateRegistrationTables extends Migration
{
    public function up()
    {
        // --- 1. TABELA DE PROFISSIONAIS (Pessoa Física) ---
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
                'null'       => true,
                'description' => 'FK opcional para acesso ao Portal do Profissional',
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'cpf' => [
                'type'       => 'VARCHAR',
                'constraint' => '14',
                'unique'     => true,
                'null'       => false,
            ],
            'registration_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'unique'     => true,
                'null'       => true,
                'description' => 'Número de registo no conselho',
            ],
            'sei_process' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'description' => 'Número do processo administrativo no SEI!',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive', 'suspended', 'deceased', 'pending'],
                'default'    => 'pending',
                'description' => 'Estado actual do registo do profissional',
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
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('professionals');

        // --- 2. TABELA DE EMPRESAS (Pessoa Jurídica) ---
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'corporate_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
                'description' => 'Razão Social',
            ],
            'trade_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'description' => 'Nome Fantasia',
            ],
            'cnpj' => [
                'type'       => 'VARCHAR',
                'constraint' => '18',
                'unique'     => true,
                'null'       => false,
            ],
            'registration_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'unique'     => true,
                'null'       => true,
            ],
            'sei_process' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive', 'suspended', 'closed', 'pending'],
                'default'    => 'pending',
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
        $this->forge->createTable('companies');
    }

    public function down()
    {
        $this->forge->dropTable('companies');
        $this->forge->dropTable('professionals');
    }
}