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
        Schema::create('whatsapp_messages', function (Blueprint $table) {
        $table->id();
        $table->string('message_id')->unique(); // ID único vindo da API do WhatsApp
        $table->string('phone_number');         // Número do remetente
        $table->string('sender_name')->nullable();
        $table->text('raw_content');            // Texto original da mensagem
        $table->boolean('passed_filter')->default(false); // Se passou no filtro inicial
        $table->json('ai_analysis')->nullable(); // Resposta JSON processada pela IA
        $table->string('status')->default('received'); // received, filtered, processed, ignored
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};
