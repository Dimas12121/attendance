@extends('layouts.app')

@section('content')
<div class="glass-card">
    <header style="text-align: center; margin-bottom: 3rem;">
        <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-bottom: 2rem;">
            @guest
                <a href="{{ route('login') }}" class="btn" style="padding: 0.5rem 1rem; font-size: 0.85rem; background: transparent; border: 1px solid var(--border);">Login</a>
                <a href="{{ route('register') }}" class="btn" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Daftar</a>
            @else
                <a href="{{ route('dashboard') }}" class="btn" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Ke Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-logout" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Logout</button>
                </form>
            @endguest
        </div>
        <span style="background: rgba(99, 102, 241, 0.1); color: var(--primary); padding: 0.5rem 1rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px;">V2.0 AI POWERED</span>
        <h1 style="font-size: 3rem; margin: 1rem 0; background: linear-gradient(to right, #818cf8, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">FaceAttend Pro</h1>
        <p style="color: var(--text-dim); font-size: 1.1rem;">Sistem Presensi Wajah & Automation Chat WhatsApp</p>
        <div id="welcome-clock" style="margin-top: 1.5rem; font-size: 2rem; font-weight: 300; color: white; letter-spacing: 2px;">
            00:00:00
        </div>
        <div id="welcome-date" style="color: var(--primary); font-size: 0.9rem; font-weight: 600; text-transform: uppercase; margin-top: 0.5rem;">
            Loading Date...
        </div>
    </header>

    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 3rem;">
        <a href="{{ route('scan') }}" class="glass-card" style="text-decoration: none; text-align: center; transition: 0.3s; background: rgba(99, 102, 241, 0.05);">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🎥</div>
            <h3 style="margin: 0; color: #fff;">Mulai Scan</h3>
            <p style="color: var(--text-dim); font-size: 0.85rem; margin-top: 0.5rem;">Buka terminal kamera presensi</p>
        </a>

        <a href="{{ route('students.index') }}" class="glass-card" style="text-decoration: none; text-align: center; transition: 0.3s;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">👥</div>
            <h3 style="margin: 0; color: #fff;">Data Siswa</h3>
            <p style="color: var(--text-dim); font-size: 0.85rem; margin-top: 0.5rem;">Kelola bio & biometrik wajah</p>
        </a>

        <a href="{{ route('logs') }}" class="glass-card" style="text-decoration: none; text-align: center; transition: 0.3s;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">📋</div>
            <h3 style="margin: 0; color: #fff;">Log Absensi</h3>
            <p style="color: var(--text-dim); font-size: 0.85rem; margin-top: 0.5rem;">Riwayat kehadiran hari ini</p>
        </a>

        <a href="{{ route('settings.index') }}" class="glass-card" style="text-decoration: none; text-align: center; transition: 0.3s;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">⚙️</div>
            <h3 style="margin: 0; color: #fff;">Pengaturan</h3>
            <p style="color: var(--text-dim); font-size: 0.85rem; margin-top: 0.5rem;">API, Waktu & Konfigurasi AI</p>
        </a>
    </div>

    <footer style="text-align: center; color: var(--text-dim); font-size: 0.85rem; border-top: 1px solid var(--border); padding-top: 2rem;">
        &copy; {{ date('Y') }} FaceAttend AI Core - Advanced Biometric System
    </footer>
</div>

<style>
    .glass-card:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.05) !important;
        border-color: var(--primary);
    }
</style>
@endsection
