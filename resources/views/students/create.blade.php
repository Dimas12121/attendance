@extends('layouts.app')

@section('content')
<div class="glass-card" style="max-width: 700px; margin: 0 auto;">
    <h2 style="margin-bottom: 2rem;">👤 Pendaftaran Siswa Baru</h2>

    <form action="{{ route('students.store') }}" method="POST">
        @csrf
        
        <div style="margin-bottom: 2.5rem; text-align: center;">
            <div style="position: relative; width: 100%; height: 350px; background: #000; border-radius: 1.5rem; overflow: hidden; border: 2px solid var(--border);">
                <video id="video" autoplay muted style="width: 100%; height: 100%; object-fit: cover;"></video>
                <div id="scan-line" style="position: absolute; top: 0; left: 0; width: 100%; height: 2px; background: var(--primary); box-shadow: 0 0 10px var(--primary); animation: scan 3s linear infinite;"></div>
            </div>
            <div id="status" style="margin-top: 1rem; color: var(--text-dim); font-size: 0.9rem;">Menyiapkan Kamera...</div>
            <button type="button" id="captureBtn" class="btn" style="margin-top: 1rem; width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--border);" disabled>Ambil Data Wajah</button>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nama Lengkap</label>
                <input type="text" name="name" required placeholder="Andi Wijaya">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">ID / NIM</label>
                <input type="text" name="student_id" required placeholder="2024001">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">WhatsApp Orang Tua</label>
                <input type="text" name="phone_parent" required placeholder="628123456789">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Tanggal Lahir</label>
                <input type="date" name="birth_date" required>
            </div>
        </div>

        <input type="hidden" name="face_descriptor" id="face_descriptor">

        <button type="submit" class="btn" style="width: 100%;">Daftarkan Siswa Sekarang</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
    const video = document.getElementById('video');
    const captureBtn = document.getElementById('captureBtn');
    const status = document.getElementById('status');
    const descriptorInput = document.getElementById('face_descriptor');

    async function init() {
        try {
            const MODEL_URL = "{{ asset('models') }}"; 
            status.innerText = "Mendownload Model AI dari: " + MODEL_URL;
            
            await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
            
            navigator.mediaDevices.getUserMedia({ video: {} }).then(stream => {
                video.srcObject = stream;
                captureBtn.disabled = false;
                status.innerText = "Sistem Biometrik Siap.";
            });
        } catch (error) {
            console.error(error);
            status.innerHTML = "<b style='color: #f87171'>GAGAL LOADING: " + error.message + "</b><br><small>Buka Console (F12) untuk detail.</small>";
        }
    }

    captureBtn.addEventListener('click', async () => {
        status.innerText = "Mendeteksi wajah...";
        const detection = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();

        if (detection) {
            descriptorInput.value = JSON.stringify(Array.from(detection.descriptor));
            status.innerHTML = "<b style='color: #4ade80'>✅ Wajah Berhasil Terdeteksi!</b>";
            captureBtn.innerText = "Ganti Foto Wajah";
        } else {
            alert("Wajah gagal dideteksi. Pastikan pencahayaan cukup.");
            status.innerText = "Gagal. Coba lagi.";
        }
    });

    init();
</script>

<style>
    @keyframes scan { from { top: 0; } to { top: 100%; } }
</style>
@endsection
