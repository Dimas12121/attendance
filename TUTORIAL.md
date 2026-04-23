# 📘 Panduan Penggunaan FaceAttend Pro v2.0

Selamat datang di panduan lengkap penggunaan **FaceAttend Pro**. Ikuti langkah-langkah di bawah ini untuk mengonfigurasi dan menggunakan sistem presensi wajah Anda dengan maksimal.

---

## 1. Persiapan Awal (Integrasi WhatsApp)

Sistem ini menggunakan **Fonnte** untuk mengirimkan notifikasi WhatsApp otomatis ke orang tua.

1.  Daftar akun di [Fonnte.com](https://fonnte.com/).
2.  Hubungkan nomor WhatsApp Anda di dashboard Fonnte.
3.  Salin **API Token** Anda.
4.  Buka file `.env` di folder proyek Anda dan tempelkan token tersebut:
    ```env
    FONNTE_TOKEN=Isi_Token_Fonnte_Anda_Disini
    ```
5.  Pastikan `APP_URL` di `.env` sudah sesuai (gunakan Ngrok jika ingin mengetes foto di WhatsApp saat masih di lokal).

---

## 2. Pengaturan Operasional Sekolah

Sebelum mulai melakukan presensi, atur jam kerja dan profil sekolah:

1.  Login ke aplikasi (Halaman `/login`).
2.  Buka menu **Settings** (ikon ⚙️).
3.  Isi **Nama Instansi** Anda.
4.  Atur **Jam Masuk Maksimal** (contoh: `07:15`). Siswa yang absen setelah jam ini akan otomatis tercatat sebagai **TERLAMBAT**.
5.  Atur **Jam Pulang** untuk referensi sistem.
6.  Klik **Simpan Pengaturan**.

---

## 3. Pendaftaran Siswa (Registrasi Wajah)

Siswa harus didaftarkan terlebih dahulu agar wajahnya dikenali oleh AI.

1.  Buka menu **Data Siswa**.
2.  Klik tombol **+ Tambah Siswa**.
3.  Isi Nama, NIS, dan **Nomor WhatsApp Orang Tua** (gunakan format internasional: `628xxxxxxxxxx`).
4.  **Registrasi Wajah**:
    *   Klik tombol **Daftarkan Wajah**.
    *   Kamera akan muncul. Pastikan pencahayaan cukup dan wajah terlihat jelas di tengah kotak.
    *   Sistem akan secara otomatis mengambil *Face Descriptor* (data biometrik wajah).
5.  Klik **Simpan Siswa**.

---

## 4. Proses Presensi (Scanning)

Halaman ini digunakan sebagai terminal di depan gerbang atau pintu masuk sekolah.

1.  Buka menu **Scan** (ikon 🎥).
2.  **Pilih Mode**:
    *   **MASUK**: Digunakan saat pagi hari (mengecek keterlambatan).
    *   **PULANG**: Digunakan saat waktu pulang.
3.  Sistem akan mendeteksi wajah secara otomatis.
4.  **Feedback Visual**:
    *   Kotak Biru 🔵: Siswa dikenali (Presensi berhasil dicatat).
    *   Kotak Merah 🔴: Wajah tidak dikenal.
5.  **Notifikasi**: Begitu wajah teridentifikasi, WhatsApp akan otomatis terkirim ke orang tua beserta foto bukti kehadiran.

---

## 5. Monitoring & Laporan

Admin dapat memantau riwayat kehadiran secara real-time:

1.  **Dashboard**: Lihat jam digital dan akses cepat ke menu utama.
2.  **Log Absensi**: Buka menu **Logs** untuk melihat daftar kehadiran hari ini.
3.  **Hapus Log**: Admin dapat menghapus log jika terjadi kesalahan input atau membersihkan seluruh log di akhir semester.

---

## 6. Tips Penggunaan AI

Agar akurasi AI maksimal:
*   Pastikan kamera memiliki resolusi minimal 720p.
*   Posisi kamera jangan menghadap ke cahaya yang menyilaukan (backlight).
*   Siswa disarankan tidak menggunakan masker saat pendaftaran wajah pertama kali.

---

## 7. Deployment ke Hosting

Jika Anda ingin meng-online-kan sistem ini:
1.  Upload kode ke hosting via FTP atau gunakan **GitHub Actions** yang sudah kami siapkan.
2.  Setelah file terupload, akses `domainanda.com/storage-link` sekali di browser.
3.  Jangan lupa ubah `APP_URL` di `.env` hosting menjadi alamat domain asli Anda agar foto di WhatsApp dapat muncul.

---
*Jika ada kendala teknis, silakan hubungi tim pengembang atau cek dokumentasi Laravel.*
