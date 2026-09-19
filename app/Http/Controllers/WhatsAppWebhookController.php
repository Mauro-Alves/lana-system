<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    /**
     * Recebe e processa os eventos enviados pela Evolution API.
     */
    public function handle(Request $request)
    {
        $data = $request->all();

        // Registra o payload recebido nos logs (storage/logs/laravel.log)
        Log::info('Webhook WhatsApp recebido:', $data);

        $event = $data['event'] ?? null;

        // Trata o evento de recebimento/atualização de mensagens
        if ($event === 'messages.upsert') {
            $messageData = $data['data'] ?? [];
            $from = $messageData['key']['remoteJid'] ?? null;
            $text = $messageData['message']['conversation'] 
                 ?? $messageData['message']['extendedTextMessage']['text'] 
                 ?? null;

            Log::info("Mensagem recebida de {$from}: {$text}");
        }

        return response()->json(['status' => 'success'], 200);
    }
}