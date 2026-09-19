<?php

namespace App\Jobs;

use App\Models\Stage;
use App\Models\Task;
use App\Models\TaskLog;
use App\Models\WhatsappMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessWhatsappMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Propriedade $message unificada no construtor.
     */
    public function __construct(public WhatsappMessage $message) {}

    public function handle(): void
    {
        // 1. Obtém o primeiro estágio de fallback (Pendentes/Triagem)
        $defaultStage = Stage::orderBy('order', 'asc')->first();

        if (!$defaultStage) {
            Log::error('ProcessWhatsappMessageJob: Nenhum estágio (Stage) encontrado no banco.');
            return;
        }

        // 2. Resgata todo o histórico de mensagens deste número de telefone
        $historyMessages = WhatsappMessage::where('phone_number', $this->message->phone_number)
            ->orderBy('created_at', 'asc')
            ->get();

        $historicoTexto = $historyMessages->map(function ($msg) {
            return "- {$msg->sender_name}: {$msg->raw_content}";
        })->implode("\n");

        $aiData = [];

        // 3. Chamada à API do Gemini alimentada pelo histórico completo
        try {
            $apiKey = config('services.gemini.key') ?? env('GEMINI_API_KEY');

            if ($apiKey) {
                $prompt = "Você é um assistente de CRM de atendimento. Analise o histórico completo de conversas no WhatsApp abaixo e atualize o contexto do chamado.

Histórico de Conversa:
{$historicoTexto}

Retorne um JSON estrito no seguinte formato sem marcações de código markdown:
{
    \"title\": \"Título resumido de até 6 palavras com nome do cliente ou assunto\",
    \"summary\": \"Resumo do estado atual da solicitação, combinados e próximos passos em 2 a 4 linhas\",
    \"priority\": \"high, medium ou low\",
    \"suggested_stage_slug\": \"triagem|atendimento|orcamento|concluido\"
}";

                $response = Http::timeout(15)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                        'contents' => [['parts' => [['text' => $prompt]]]]
                    ]);

                if ($response->successful()) {
                    $jsonText = $response->json('candidates.0.content.parts.0.text') ?? '{}';
                    $cleanedJson = preg_replace('/```json\s*|\s*```/', '', trim($jsonText));
                    $aiData = json_decode($cleanedJson, true) ?? [];
                } else {
                    Log::warning("Gemini respondeu com erro HTTP: " . $response->body());
                }
            }
        } catch (Throwable $e) {
            Log::warning("Gemini indisponível ou falhou no Job: " . $e->getMessage());
        }

        // 4. Normalização de prioridade para ENUM do banco
        $priorityMap = [
            'alta'   => 'high',
            'media'  => 'medium',
            'baixa'  => 'low',
            'high'   => 'high',
            'medium' => 'medium',
            'low'    => 'low',
        ];
        $rawPriority = mb_strtolower($aiData['priority'] ?? 'medium');
        $taskPriority = $priorityMap[$rawPriority] ?? 'medium';

        // 5. Atualiza o status e análise da mensagem processada
        $this->message->update([
            'ai_analysis' => $aiData,
            'status'      => 'processed',
        ]);

        // 6. Mapeamento de estágio sugerido pela IA ou fallback
        $stageSlug = $aiData['suggested_stage_slug'] ?? null;
        $targetStage = $stageSlug ? Stage::where('slug', $stageSlug)->first() : null;
        $finalStageId = $targetStage?->id ?? $defaultStage->id;

        // 7. Busca a Task existente pelo telefone ou vincula/cria uma nova
        $task = Task::whereHas('message', function ($query) {
            $query->where('phone_number', $this->message->phone_number);
        })->latest()->first();

        if ($task) {
            // Atualiza a Task existente com o novo resumo/contexto retornado pela IA
            $task->update([
                'title'       => $aiData['title'] ?? $task->title,
                'summary'     => $aiData['summary'] ?? $task->summary,
                'description' => $task->description ?: ($aiData['summary'] ?? $this->message->raw_content),
                'priority'    => $taskPriority,
            ]);

            TaskLog::create([
                'task_id' => $task->id,
                'user_id' => null,
                'action'  => "Resumo dinâmico e contexto atualizados via IA após nova interação.",
            ]);
        } else {
            // Se for a primeira interação do cliente, cria a Task
            $newTask = Task::create([
                'whatsapp_message_id' => $this->message->id,
                'stage_id'            => $finalStageId,
                'title'               => $aiData['title'] ?? ('Atendimento - ' . $this->message->sender_name),
                'description'         => $this->message->raw_content,
                'summary'             => $aiData['summary'] ?? $this->message->raw_content,
                'priority'            => $taskPriority,
                'status'              => 'open',
            ]);

            TaskLog::create([
                'task_id' => $newTask->id,
                'user_id' => null,
                'action'  => "Card criado automaticamente via IA.",
            ]);
        }
    }
}