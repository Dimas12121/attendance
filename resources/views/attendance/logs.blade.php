@extends('layouts.app')

@section('content')
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 2rem;">📋 Log Presensi Kehadiran</h2>
            <p style="color: var(--text-dim); margin-top: 0.5rem;">Riwayat kehadiran siswa secara real-time.</p>
        </div>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <a href="/" style="color: var(--text-dim); text-decoration: none; padding: 0.75rem;">← Dashboard</a>
            <form action="{{ route('logs.clear') }}" method="POST" onsubmit="return confirm('Hapus SELURUH riwayat presensi? Tindakan ini tidak bisa dibatalkan.')">
                @csrf @method('DELETE')
                <button type="submit" class="btn" style="background: rgba(239, 68, 68, 0.1); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2); font-size: 0.8rem;">🗑️ Bersihkan Semua</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div style="background: rgba(34, 197, 94, 0.1); color: #4ade80; padding: 1rem; border-radius: 1rem; margin-bottom: 2rem; border: 1px solid rgba(34, 197, 94, 0.2);">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Waktu Deteksi</th>
                    <th>Nama Siswa</th>
                    <th>ID Siswa</th>
                    <th>Jenis Absen</th>
                    <th>Bukti Foto</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="color: #fff; font-weight: 600;">
                        {{ \Carbon\Carbon::parse($log->logged_at)->format('d M / H:i:s') }}
                    </td>
                    <td>
                        <div style="font-weight: 700;">{{ $log->student->name }}</div>
                    </td>
                    <td>
                        <code style="background: rgba(255,255,255,0.05); padding: 0.3rem 0.6rem; border-radius: 0.5rem; color: var(--primary);">{{ $log->student->student_id }}</code>
                    </td>
                    <td>
                        <span class="badge" style="background: {{ $log->type == 'in' ? 'rgba(34, 197, 94, 0.1)' : 'rgba(99, 102, 241, 0.1)' }}; color: {{ $log->type == 'in' ? '#4ade80' : 'var(--primary)' }};">
                            {{ strtoupper($log->type) }}
                        </span>
                    </td>
                    <td>
                        @if($log->photo)
                            <img src="{{ asset('storage/' . $log->photo) }}" 
                                 onclick="showPhoto('{{ asset('storage/' . $log->photo) }}')"
                                 style="width: 80px; cursor: pointer; border-radius: 8px; border: 1px solid var(--border); transition: 0.3s;"
                                 onmouseover="this.style.transform='scale(1.1)'"
                                 onmouseout="this.style.transform='scale(1)'">
                        @else
                            -
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <form action="{{ route('logs.destroy', $log) }}" method="POST" onsubmit="return confirm('Hapus log ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #f87171; cursor: pointer; font-size: 1rem;">✕</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 4rem; color: var(--text-dim);">
                        Belum ada riwayat absen untuk ditampilkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 2rem;">
        {{ $logs->links() }}
    </div>
</div>

<!-- Premium Photo Modal -->
<div id="photoModal" onclick="this.style.display='none'" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.9); z-index: 9999; backdrop-filter: blur(15px); align-items: center; justify-content: center; padding: 2rem; cursor: zoom-out;">
    <div style="position: relative; max-width: 90%; max-height: 90vh;">
        <img id="modalImg" src="" style="width: 100%; border-radius: 1.5rem; border: 2px solid var(--primary); box-shadow: 0 0 50px rgba(99, 102, 241, 0.4);">
        <p style="text-align: center; color: white; margin-top: 1rem; font-size: 0.9rem; opacity: 0.7;">Klik di mana saja untuk menutup</p>
    </div>
</div>

<script>
    function showPhoto(src) {
        document.getElementById('modalImg').src = src;
        document.getElementById('photoModal').style.display = 'flex';
    }
</script>
@endsection
