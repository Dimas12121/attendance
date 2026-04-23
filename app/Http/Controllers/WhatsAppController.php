<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    protected $whatsapp;
    protected $gemini;

    public function __construct(WhatsAppService $whatsapp, GeminiService $gemini)
    {
        $this->whatsapp = $whatsapp;
        $this->gemini = $gemini;
    }

    /**
     * Handle incoming webhook from Fonnte
     */
    public function webhook(Request $request)
    {
        Log::info("DEBUG: Webhook Hit!", $request->all());

        // Extract data based on Fonnte webhook format
        $sender = $request->input('sender'); // Phone number
        $message = $request->input('message'); // User message

        if (!$sender || !$message) {
            return response()->json(['status' => 'ignored'], 200);
        }

        Log::info("Incoming WA Message from $sender: $message");

        // 0. Check if AI is enabled
        $isEnabled = \App\Models\Setting::get('ai_enabled', '1');
        if ($isEnabled !== '1') {
            Log::info("AI Automation is disabled. Skipping.");
            return response()->json(['status' => 'disabled'], 200);
        }

        // 1. Get system instruction
        $instruction = \App\Models\Setting::get('ai_instruction', 'Anda adalah asisten AI yang ramah.');
        
        // 1.1 Context: Get latest 5 attendances to help AI answer
        $latestAttendances = \App\Models\Attendance::with('student')->latest()->take(5)->get()->map(function($a) {
            return "{$a->student->name} ({$a->student->student_id}) absen {$a->type} pada {$a->logged_at}";
        })->implode(", ");
        
        $context = "DATA PRESENSI TERBARU: " . ($latestAttendances ?: "Belum ada data.");
        $promptWithContext = "Konteks Data:\n$context\n\nUser: $message";

        // 2. Get response from Gemini AI
        $aiResponse = $this->gemini->generateResponse($promptWithContext, $instruction);
        
        Log::info("AI Response for $sender: $aiResponse");

        // 3. Send the AI response back to WhatsApp
        $this->whatsapp->sendMessage($sender, $aiResponse);

        return response()->json(['status' => 'success'], 200);
    }
}
