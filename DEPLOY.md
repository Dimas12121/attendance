# 🚀 Panduan Deployment FaceAttend Pro

Panduan ini menjelaskan cara memindahkan aplikasi dari komputer lokal ke server (Shared Hosting/CPanel) menggunakan metode FTP atau GitHub Actions.

---

## Opsi 1: Upload Manual via FTP (FileZilla/cPanel)

Jika Anda ingin mengupload file secara langsung ke hosting:

### 1. Persiapan File di Lokal
Sebelum upload, jalankan perintah ini di komputer Anda:
```bash
# Compile aset agar siap pakai di hosting
npm run build

# Jika ingin upload folder vendor, pastikan sudah teroptimasi
composer install --optimize-autoloader --no-dev
```

### 2. Struktur Folder Hosting
Disarankan untuk meletakkan file di luar folder `public_html` demi keamanan, namun jika Anda ingin praktis (menggunakan `.htaccess` yang sudah saya buat):

1.  Upload **seluruh folder proyek** Anda ke dalam folder `public_html`.
2.  Pastikan file `.htaccess` (yang ada di root folder) ikut ter-upload. File ini berfungsi mengarahkan semua trafik ke folder `public`.

### 3. Konfigurasi Database (MySQL)
Jika di hosting Anda menggunakan MySQL:
1.  Buat database baru di cPanel (MySQL Databases).
2.  Export database lokal Anda ke file `.sql` dan Import ke phpMyAdmin di hosting.
3.  Sesuaikan file `.env` di hosting:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_anda
    DB_USERNAME=username_database_anda
    DB_PASSWORD=password_database_anda
    ```

### 4. Hubungkan Folder Foto (Storage Link)
Setelah file terupload, folder foto tidak akan muncul di WhatsApp jika belum di-link. 
*   **Cara Cepat**: Buka browser dan akses: `https://domainanda.com/storage-link`.
*   Akan muncul pesan "Storage link created successfully".

---

## Opsi 2: Deploy Otomatis via GitHub Actions (Rekomendasi)

Ini adalah cara paling modern. Anda cukup `git push`, dan hosting akan terupdate otomatis.

### 1. Setting GitHub Secrets
Buka repositori Anda di GitHub, pergi ke **Settings > Secrets and variables > Actions**, lalu tambahkan:
-   `FTP_SERVER`: Alamat Host FTP (Contoh: `ftp.domainanda.com`)
-   `FTP_USERNAME`: Username FTP Anda.
-   `FTP_PASSWORD`: Password FTP Anda.

### 2. File Workflow
Pastikan file `.github/workflows/main.yml` sudah ada (sudah saya buatkan sebelumnya).

### 3. Cara Deploy
Setiap kali Anda selesai melakukan perubahan di komputer lokal, jalankan:
```bash
git add .
git commit -m "update fitur baru"
git push origin main
```
GitHub akan otomatis menjalankan proses upload ke hosting Anda. Anda bisa memantau prosesnya di tab **Actions** di GitHub.

---

## 💡 Masalah yang Sering Muncul (Troubleshooting)

1.  **Error 500 (Internal Server Error)**:
    *   Pastikan versi PHP di hosting minimal **8.2**.
    *   Cek file `.env`, pastikan tidak ada spasi yang salah.
    *   Pastikan folder `storage` dan `bootstrap/cache` memiliki izin akses (**Permission 775 atau 755**).
2.  **Foto di WA tidak muncul**:
    *   Pastikan `APP_URL` di `.env` sudah menggunakan `https://domainanda.com`.
    *   Pastikan Anda sudah mengakses `domainanda.com/storage-link`.
3.  **Halaman Putih Polos**:
    *   Jalankan `php artisan view:clear` dan `php artisan config:clear` (atau lewat helper route jika tidak ada SSH).

---
*Selamat mencoba! Jika ada kendala, hubungi pengembang.*
