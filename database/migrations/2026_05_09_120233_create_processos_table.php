<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('processos', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('tipo_processo_id')->constrained('tipos_processos');
            $table->foreignId('interessado_id')->nullable()->constrained('users');
            $table->string('assunto');
            $table->text('descricao')->nullable();
            $table->enum('nivel_acesso', ['publico', 'restrito', 'sigiloso'])->default('publico');
            $table->enum('status', ['aberto', 'em_analise', 'concluido', 'arquivado'])->default('aberto');
            $table->dateTime('data_abertura')->useCurrent();
            $table->dateTime('data_fechamento')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processos');
    }
};
