@extends('layouts.app')

@section('content')
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2>⚙️ Pengaturan Sistem</h2>
            <p style="color: var(--text-dim); margin-top: 0.5rem;">Konfigurasi identitas, waktu, dan integrasi AI.</p>
        </div>
        <a href="/" class="btn" style="background: rgba(255,255,255,0.05); color: #fff;">← Kembali</a>
    </div>

    @if(session('success'))
        <div style="background: rgba(34, 197, 94, 0.1); color: #4ade80; padding: 1rem; border-radius: 1rem; margin-bottom: 2rem; border: 1px solid rgba(34, 197, 94, 0.2);">
            ✅ {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('settings.store') }}" method="POST">
        @csrf
        
        <!-- KATEGORI 1: PROFIL & WAKTU -->
        <h3 style="color: var(--primary); margin-top: 2rem;">🏫 Profil & Operasional</h3>
        <div class="glass-card" style="background: rgba(255,255,255,0.01); padding: 1.5rem; margin-bottom: 2rem;">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem;">Nama Sekolah / Instansi</label>
                <input type="text" name="school_name" value="{{ $settings['school_name'] }}" placeholder="Contoh: SMA Negeri 1 Jakarta">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem;">Jam Masuk Maksimal</label>
                    <input type="time" name="check_in_time" value="{{ $settings['check_in_time'] }}">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem;">Jam Pulang</label>
                    <input type="time" name="check_out_time" value="{{ $settings['check_out_time'] }}">
                </div>
            </div>
        </div>

        <!-- KATEGORI 2: INTEGRASI API -->
        <h3 style="color: var(--primary);">🔑 API & Integrasi</h3>
        <div class="glass-card" style="background: rgba(255,255,255,0.01); padding: 1.5rem; margin-bottom: 2rem;">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem;">WhatsApp Token (Fonnte)</label>
                <input type="password" name="fonnte_token" value="{{ $settings['fonnte_token'] }}" placeholder="Masukkan Token Fonnte Anda">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem;">Google Gemini API Key</label>
                <input type="password" name="gemini_api_key" value="{{ $settings['gemini_api_key'] }}" placeholder="Masukkan Kunci API Gemini">
            </div>
        </div>

        <!-- KATEGORI 3: AI CHATBOT -->
        <h3 style="color: var(--primary);">🤖 AI Chatbot (WhatsApp)</h3>
        <div class="glass-card" style="background: rgba(255,255,255,0.01); padding: 1.5rem; margin-bottom: 2rem;">
            <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <label class="switch" style="position: relative; display: inline-block; width: 50px; height: 26px;">
                    <input type="checkbox" name="ai_enabled" value="1" {{ $settings['ai_enabled'] == '1' ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0;">
                    <span class="slider"></span>
                </label>
                <span>Aktifkan Respon AI Otomatis</span>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem;">Instruksi AI (Prompting)</label>
                <textarea name="ai_instruction" rows="4">{{ $settings['ai_instruction'] }}</textarea>
                <small style="color: var(--text-dim);">Tentukan bagaimana AI harus membalas pesan orang tua (Gaya bahasa, keramahan, dll).</small>
            </div>
        </div>

        <button type="submit" class="btn" style="width: 100%; font-size: 1.1rem; padding: 1rem;">💾 Simpan Semua Perubahan</button>
    </form>
</div>

<style>
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background: #334155; transition: .4s; border-radius: 34px; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 4px; bottom: 4px; background: white; transition: .4s; border-radius: 50%; }
    input:checked + .slider { background: var(--primary); }
    input:checked + .slider:before { transform: translateX(24px); }
</style>
@endsection
