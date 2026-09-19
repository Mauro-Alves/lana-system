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
        Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('whatsapp_message_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Usuário responsável
        $table->foreignId('stage_id')->constrained(); // Etapa atual do Kanban
        
        $table->string('title');                // Título resumido gerado pela IA
        $table->text('description')->nullable(); // Detalhes extraídos pela IA
        $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
        
        $table->timestamp('due_date')->nullable();
        $table->timestamp('completed_at')->nullable(); // Data em que o usuário deu baixa
        $table->timestamps(); // includes created_at (usado para ordem cronológica)
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
