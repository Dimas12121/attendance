@extends('layouts.app')

@section('content')
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 2rem;">👥 Data Siswa</h2>
            <p style="color: var(--text-dim); margin-top: 0.5rem;">Total: {{ $students->count() }} terdaftar.</p>
        </div>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <form action="{{ route('students.index') }}" method="GET" style="display: flex; gap: 0.5rem; align-items: center;">
                <select name="status" onchange="this.form.submit()" style="padding: 0.6rem; font-size: 0.85rem; width: 150px; background: rgba(0,0,0,0.2); color: blue; border: 1px solid var(--border); border-radius: 0.5rem;">
                    <option value="">Semua Status</option>
                    <option value="registered" {{ ($status ?? '') == 'registered' ? 'selected' : '' }}>Sudah Daftar</option>
                    <option value="not_registered" {{ ($status ?? '') == 'not_registered' ? 'selected' : '' }}>Belum Daftar</option>
                </select>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Nama/ID..." style="width: 150px; padding: 0.6rem; font-size: 0.85rem;">
                <button type="submit" class="btn" style="padding: 0.6rem 1rem;">Filter</button>
                @if($search || $status)
                    <a href="{{ route('students.index') }}" class="btn" style="background: rgba(255,255,255,0.05); color: #fff; padding: 0.6rem; text-decoration: none;">✕</a>
                @endif
            </form>
            <a href="{{ route('students.create') }}" class="btn">+ Siswa Baru</a>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="btn"><- Kembali</a>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>WhatsApp Orang Tua</th>
                    <th>Status Wajah</th>
                    <th>Terdaftar Pada</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr>
                    <td>
                        <div style="font-weight: 700; color: #fff;">{{ $student->name }}</div>
                        <div style="color: var(--primary); font-size: 0.8rem; font-weight: 600;">#{{ $student->student_id }}</div>
                    </td>
                    <td style="color: var(--text-dim);">
                        {{ $student->phone_parent }}
                    </td>
                    <td>
                        @if($student->face_descriptor)
                            <span class="badge" style="background: rgba(34, 197, 94, 0.1); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.2);">Terdaftar</span>
                        @else
                            <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2);">Belum Ada</span>
                        @endif
                    </td>
                    <td style="color: var(--text-dim); font-size: 0.9rem;">
                        {{ $student->created_at->format('d M Y') }}
                    </td>
                    <td style="display: flex; gap: 0.5rem; justify-content: center;">
                        <a href="{{ route('students.edit', $student) }}" class="btn" style="padding: 0.5rem; font-size: 0.75rem; background: rgba(99, 102, 241, 0.1); color: var(--primary);">Edit</a>
                        <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Hapus siswa ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn" style="padding: 0.5rem; font-size: 0.75rem; background: rgba(239, 68, 68, 0.1); color: #f87171;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 4rem; color: var(--text-dim);">
                        Belum ada data siswa. Silakan tambah siswa baru.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
