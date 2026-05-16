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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processo_id')->constrained('processos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->string('titulo');
            $table->string('tipo_documento')->default('pdf'); // pdf, html, despacho, etc.
            $table->string('arquivo_path')->nullable(); // Caminho para o PDF no storage
            $table->longText('conteudo')->nullable(); // Conteúdo se for documento interno (HTML)
            $table->enum('nivel_acesso', ['publico', 'restrito', 'sigiloso'])->default('publico');
            $table->enum('status', ['rascunho', 'assinado', 'cancelado'])->default('rascunho');
            $table->string('numero_documento')->unique()->nullable(); // Numeração interna similar ao SEI
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
