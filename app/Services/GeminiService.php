<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $url;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        $this->url = config('services.gemini.url');
    }

    /**
     * Generate AI response from user message
     */
    public function generateResponse($userMessage, $systemInstruction = null)
    {
        if (!$this->apiKey) {
            Log::warning("GeminiService: API Key missing.");
            return "Maaf, sistem AI sedang tidak aktif. Silakan hubungi admin.";
        }

        // Prepend instructions to the message for maximum compatibility
        $fullPrompt = $userMessage;
        if ($systemInstruction) {
            $fullPrompt = "Instructions: " . $systemInstruction . "\n\nUser: " . $userMessage . "\n\nResponse:";
        }

        try {
            // Using Header for API Key instead of URL parameter for better reliability
            $response = Http::withHeaders([
                'x-goog-api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $fullPrompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 800,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? "Maaf, saya tidak bisa memproses pesan tersebut.";
            }

            Log::error("Gemini API Error Response: " . $response->body());
            return "Maaf, ada kendala teknis saat menghubungi AI.";
        } catch (\Exception $e) {
            Log::error("GeminiService Exception: " . $e->getMessage());
            return "Terjadi kesalahan internal pada sistem AI.";
        }
    }
}
