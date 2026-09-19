<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $instance;

    public function __construct()
    {
        $this->baseUrl  = config('services.whatsapp.base_url');
        $this->apiKey   = config('services.whatsapp.api_key');
        $this->instance = config('services.whatsapp.instance');
    }

    /**
     * Envia uma mensagem de texto simples para um número.
     */
    public function sendMessage(string $phoneNumber, string $message): bool
    {
        try {
            $url = rtrim($this->baseUrl, '/') . "/message/sendText/{$this->instance}";

            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->post($url, [
                'number'      => $phoneNumber,
                'textMessage' => [
                    'text' => $message,
                ],
            ]);

            if ($response->failed()) {
                Log::error("Erro ao enviar mensagem via WhatsApp: " . $response->body());
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Exceção ao enviar mensagem via WhatsApp: " . $e->getMessage());
            return false;
        }
    }
}