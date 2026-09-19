<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessWhatsappMessageJob;
use App\Models\Task;
use App\Models\WhatsappMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WhatsappWebhookController extends Controller
{
    /**
     * Palavras-chave ou intenções que classificam o webhook como relevante.
     */
    protected array $keywords = [
        // Tipos de Eventos
        'festa', 'evento', 'casamento', 'infantil', 'adulto', 'batizado', 
        'aniversario', 'aniversário', 'chá de bebê', 'cha de bebe', '15 anos', 'debutante',
        
        // Termos de Locação e Intenção de Reserva
        'locação', 'locacao', 'aluguel', 'alugar', 'reserva', 'reservar', 
        'disponibilidade', 'data', 'orcamento', 'orçamento', 'valor', 'preço', 'preco',
        
        // Itens Comuns do Acervo
        'pegue e monte', 'ornamentacao', 'ornamentação', 'decoracao', 'decoração', 
        'painel', 'mesa', 'balão', 'balao', 'kit'
    ];

    /**
     * Processa as notificações de mensagens vindas da API do WhatsApp.
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        // Extração com validações seguras dos dados do payload
        $messageId  = $payload['data']['key']['id'] ?? null;
        $fromMe     = $payload['data']['key']['fromMe'] ?? false;
        $remoteJid  = $payload['data']['key']['remoteJid'] ?? '';
        $senderName = $payload['data']['pushName'] ?? ($fromMe ? 'Atendente' : 'Cliente');
        $content    = $payload['data']['message']['conversation'] 
            ?? $payload['data']['message']['extendedTextMessage']['text'] 
            ?? null;

        // Validação básica do conteúdo e ID
        if (empty($content) || empty($messageId)) {
            return response()->json([
                'status'  => 'ignored',
                'message' => 'Conteúdo ou ID de mensagem inválido.'
            ], 200);
        }

        // Extrai apenas os números do WhatsApp do cliente (funciona para mensagens enviadas e recebidas)
        $phoneNumber = preg_replace('/[^0-9]/', '', str_replace('@s.whatsapp.net', '', $remoteJid));

        // TRATAMENTO PARA MENSAGENS RESPONDIDAS (OUTGOING / FROM ME = TRUE)
        if ($fromMe) {
            return $this->handleOutgoingMessage($messageId, $phoneNumber, $senderName, $content);
        }

        // TRATAMENTO PARA MENSAGENS RECEBIDAS DO CLIENTE (INCOMING)
        $passedFilter = $this->shouldProcessMessage($content);

        // Previne erro de Duplicate Entry usando firstOrCreate
        $message = WhatsappMessage::firstOrCreate(
            ['message_id' => $messageId],
            [
                'phone_number'  => $phoneNumber,
                'sender_name'   => $senderName,
                'raw_content'   => $content,
                'passed_filter' => $passedFilter,
                'status'        => $passedFilter ? 'filtered' : 'ignored',
            ]
        );

        // Se a mensagem já existia no banco, responde sem reprocessar
        if (!$message->wasRecentlyCreated) {
            return response()->json([
                'status'     => 'already_exists',
                'message_id' => $message->id,
                'reason'     => 'Mensagem duplicada já cadastrada anteriormente.'
            ], 200);
        }

        // Se passou no filtro inicial, envia para a fila do Gemini
        if ($passedFilter) {
            ProcessWhatsappMessageJob::dispatch($message);
        }

        return response()->json([
            'status'        => 'success',
            'passed_filter' => $passedFilter,
            'message_id'    => $message->id,
            'reason'        => $passedFilter ? 'Enviado para a fila de IA.' : 'Descartado pelo filtro inicial.'
        ], 200);
    }

    /**
     * Processa respostas enviadas pela empresa/atendente para o cliente.
     */
    protected function handleOutgoingMessage(string $messageId, string $phoneNumber, string $senderName, string $content): JsonResponse
    {
        // 1. Registra a mensagem no banco
        $message = WhatsappMessage::firstOrCreate(
            ['message_id' => $messageId],
            [
                'phone_number'  => $phoneNumber,
                'sender_name'   => $senderName,
                'raw_content'   => "[Resposta Enviada] " . $content,
                'passed_filter' => true,
                'status'        => 'replied',
            ]
        );

        if (!$message->wasRecentlyCreated) {
            return response()->json(['status' => 'already_exists'], 200);
        }

        // 2. Busca o card ativo do cliente pelo número de telefone
        $task = Task::whereHas('message', function ($query) use ($phoneNumber) {
            $query->where('phone_number', $phoneNumber);
        })->latest()->first();

        // 3. Se encontrar a task do cliente, atualiza a data de resposta e envia para re-sumarização da IA
        if ($task) {
            $task->update([
                'last_replied_at' => now(),
            ]);

            // Dispara a atualização do resumo inteligente com o novo contexto
            ProcessWhatsappMessageJob::dispatch($message);
        }

        return response()->json([
            'status'     => 'success',
            'type'       => 'outgoing_processed',
            'task_id'    => $task?->id,
            'message_id' => $message->id,
        ], 200);
    }

    /**
     * Verifica se o conteúdo possui intenções ou termos relevantes.
     */
    protected function shouldProcessMessage(string $content): bool
    {
        $normalizedContent = Str::lower($content);

        foreach ($this->keywords as $keyword) {
            if (Str::contains($normalizedContent, $keyword)) {
                return true;
            }
        }

        return false;
    }
}