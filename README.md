# 🖥️ FaceAttend Pro v2.0 - AI Biometric Attendance System

**FaceAttend Pro** adalah sistem presensi modern berbasis Web yang menggunakan teknologi **AI Face Recognition** (Face-api.js) untuk identitas biometrik dan otomatisasi notifikasi WhatsApp melalui **Fonnte**.

![Version](https://img.shields.io/badge/version-2.0-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-13.x-red.svg)
![AI](https://img.shields.io/badge/AI-Face--api.js-green.svg)
![License](https://img.shields.io/badge/license-MIT-brightgreen.svg)

---

## ✨ Fitur Unggulan

- 🤖 **AI Face Recognition**: Deteksi dan rekognisi wajah secara real-time langsung melalui browser.
- 📹 **Multi-Cam Support**: Mendukung pemantauan dari berbagai input kamera sekaligus.
- 💬 **Otomatisasi WhatsApp**: Kirim notifikasi kehadiran (Masuk/Pulang/Terlambat) ke orang tua secara instan beserta foto bukti.
- 🔐 **Secure Authentication**: Sistem login aman untuk admin menggunakan Laravel Breeze.
- 📊 **Real-time Dashboard**: Panel pemantauan yang hidup dengan jam digital dan log aktivitas instan.
- 📱 **Responsive Design**: Tampilan premium "Glassmorphism" yang optimal di desktop maupun perangkat mobile.
- ☁️ **Auto Deployment**: Terintegrasi dengan GitHub Actions untuk deploy otomatis ke hosting FTP.

---

## 🚀 Teknologi yang Digunakan

- **Backend**: Laravel 13.x
- **Frontend**: Blade, CSS Vanilla (Glassmorphism), JavaScript
- **AI Engine**: [Face-api.js](https://github.com/justadudewhohacks/face-api.js/)
- **WhatsApp Gateway**: [Fonnte API](https://fonnte.com/)
- **State Management**: Alpine.js
- **Database**: SQLite / MySQL

---

## 🛠️ Instalasi Lokal

1. **Clone Repositori**:
   ```bash
   git clone https://github.com/username/attendance.git
   cd attendance
   ```

2. **Instal Dependensi**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**:
   Salin file `.env.example` menjadi `.env` dan atur konfigurasi berikut:
   ```env
   APP_URL=http://localhost:8000
   APP_TIMEZONE=Asia/Jakarta
   
   FONNTE_TOKEN=your_token_here
   ```

4. **Setup Database & Storage**:
   ```bash
   php artisan key:generate
   php artisan migrate
   php artisan storage:link
   ```

5. **Jalankan Aplikasi**:
   ```bash
   npm run build
   php artisan serve
   ```

---

## 🚢 Panduan Deployment (FTP)

Sistem ini mendukung **Auto-Deploy** via GitHub Actions.

1. Simpan kode Anda di GitHub.
2. Atur **GitHub Secrets** di repositori Anda:
   - `FTP_SERVER`: Alamat host FTP.
   - `FTP_USERNAME`: Username akun FTP.
   - `FTP_PASSWORD`: Password akun FTP.
3. Setiap kali Anda melakukan `git push origin main`, kode akan otomatis ter-update di hosting.
4. Akses `yourdomain.com/storage-link` satu kali untuk mengaktifkan folder foto di server.

---

## 📸 Dokumentasi & Penggunaan

1. **Scan Wajah**: Masuk ke menu **Scan**, pilih kamera, dan arahkan wajah ke kamera.
2. **Tambah Siswa**: Kelola data siswa dan biometrik wajah di menu **Data Siswa**.
3. **Pengaturan**: Atur jam masuk maksimal dan token API di menu **Settings**.

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---
*Dibuat dengan ❤️ oleh Antigravity AI untuk solusi presensi cerdas.*
