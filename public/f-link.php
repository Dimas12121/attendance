<?php
// Script darurat untuk membuat storage link tanpa lewat Laravel Router
$target = __DIR__ . '/../storage/app/public';
$link = __DIR__ . '/storage';

if (file_exists($link)) {
    if (is_link($link)) {
        unlink($link);
        echo "Link lama dihapus.<br>";
    } else {
        echo "Error: Folder 'storage' sudah ada tapi bukan link. Hapus folder 'public/storage' dulu secara manual.<br>";
        exit;
    }
}

if (symlink($target, $link)) {
    echo "Storage link berhasil dibuat!<br>";
    echo "Target: $target<br>";
    echo "Link: $link";
} else {
    echo "Gagal membuat symlink. Pastikan fungsi 'symlink' diizinkan di hosting Anda.";
}
