<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $token;
    protected $baseUrl;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
        $this->baseUrl = config('services.fonnte.url');
    }

    /**
     * Send a message via Fonnte (supports text and image)
     */
    public function sendMessage($target, $message, $url = null)
    {
        if (!$this->token || !$target) {
            Log::warning("WhatsAppService: Token or target missing.");
            return false;
        }

        try {
            $data = [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
            ];

            if ($url) {
                $data['url'] = $url;
            }

            Log::info("Sending Fonnte Request: ", $data);

            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->baseUrl, $data);

            Log::info("Fonnte API Response: " . $response->body());
            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Fonnte API Error: " . $e->getMessage());
            return false;
        }
    }
}
