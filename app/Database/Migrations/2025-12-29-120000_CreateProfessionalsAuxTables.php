<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProfessionalsAuxTables extends Migration
{
    public function up()
    {
        // --- 1. TABELA DE ENDEREÇOS DOS PROFISSIONAIS ---
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'profissional_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'cep' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => true,
            ],
            'logradouro' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'numero' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'complemento' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'bairro' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'cidade' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'uf' => [
                'type'       => 'VARCHAR',
                'constraint' => '2',
                'null'       => true,
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
        $this->forge->addForeignKey('profissional_id', 'professionals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('professionals_addresses');

        // --- 2. TABELA DE PROFISSÕES ---
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('profissoes');

        // PIVOT: PROFISSIONAIS <-> PROFISSÕES
        $this->forge->addField([
            'profissional_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'profissao_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addForeignKey('profissional_id', 'professionals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('profissao_id', 'profissoes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('profissional_profissoes');


        // --- 3. TABELA DE CATEGORIAS DE PROFISSIONAIS ---
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('categorias_profissionais');

        // PIVOT: PROFISSIONAIS <-> CATEGORIAS
        $this->forge->addField([
            'profissional_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'categoria_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addForeignKey('profissional_id', 'professionals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('categoria_id', 'categorias_profissionais', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('profissional_categorias');


        // --- 4. TABELA DE ATRIBUIÇÕES ---
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('atribuicoes');

        // PIVOT: PROFISSIONAIS <-> ATRIBUIÇÕES
        $this->forge->addField([
            'profissional_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'atribuicao_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addForeignKey('profissional_id', 'professionals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('atribuicao_id', 'atribuicoes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('profissional_atribuicoes');
    }

    public function down()
    {
        $this->forge->dropTable('profissional_atribuicoes');
        $this->forge->dropTable('atribuicoes');
        $this->forge->dropTable('profissional_categorias');
        $this->forge->dropTable('categorias_profissionais');
        $this->forge->dropTable('profissional_profissoes');
        $this->forge->dropTable('profissoes');
        $this->forge->dropTable('professionals_addresses');
    }
}
