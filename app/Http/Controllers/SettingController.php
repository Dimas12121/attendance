<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function dashboard()
    {
        return view('welcome');
    }

    public function index()
    {
        $settings = [
            'school_name' => Setting::get('school_name', 'FaceAttend High School'),
            'ai_enabled' => Setting::get('ai_enabled', '1'),
            'ai_instruction' => Setting::get('ai_instruction', 'Anda adalah asisten AI yang ramah.'),
            'check_in_time' => Setting::get('check_in_time', '07:00'),
            'check_out_time' => Setting::get('check_out_time', '15:00'),
            'fonnte_token' => Setting::get('fonnte_token', env('FONNTE_TOKEN')),
            'gemini_api_key' => Setting::get('gemini_api_key', env('GEMINI_API_KEY')),
        ];
        
        return view('settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $settings = $request->all();
        
        // Handle Switch (checkbox)
        Setting::set('ai_enabled', $request->has('ai_enabled') ? '1' : '0');

        foreach($settings as $key => $value) {
            if($key != '_token' && $key != 'ai_enabled') {
                Setting::set($key, $value);
            }
        }

        return back()->with('success', 'Konfigurasi berhasil disimpan!');
    }
}
