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
        Schema::create('stages', function (Blueprint $table) {
        $table->id();
        $table->string('name');              // Ex: A Fazer, Em Progresso, Concluído
        $table->string('color')->default('#e5e7eb'); // Cor do card/coluna no front
        $table->integer('order')->default(0); // Para ordenação das colunas
        $table->boolean('is_final')->default(false); // Define se é etapa de baixa/conclusão
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stages');
    }
};
