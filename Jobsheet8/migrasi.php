<?php
// 1. Load file koneksi database (sesuai struktur folder jobsheet-08)
require_once dirname(__FILE__) . '/includes/koneksi.php';

// 2. Tentukan jalur ke file JSON dari jobsheet-06
$fileJson = dirname(__FILE__) . '/../Jobsheet6/data/buku.json';

// Cek apakah file JSON-nya ada
if (!file_exists($fileJson)) {
    die("Error: File {$fileJson} tidak ditemukan!");
}

// 3. Baca isi file JSON & ubah (decode) menjadi array asosiatif PHP
$dataJson = file_get_contents($fileJson);
$daftarBuku = json_decode($dataJson, true);

// Memastikan data JSON berhasil di-decode dan bentuknya array
if (!is_array($daftarBuku)) {
    die("Error: Gagal membaca data dari JSON atau format file salah.");
}

try {
    // 4. Query INSERT dengan prepared statement
    $sql = "INSERT INTO buku (judul, pengarang, tahun, stok, kategori) VALUES (:judul, :pengarang, :tahun, :stok, :kategori)";
    $stmt = $pdo->prepare($sql);

    $jumlahBerhasil = 0;

    // 5. Looping setiap data buku dari JSON dan masukkan ke database
    foreach ($daftarBuku as $buku) {
        $stmt->execute([
            ':judul'        => $buku['judul'],
            ':pengarang'      => $buku['pengarang'],
            ':tahun' => $buku['tahun'],
            ':stok' => $buku['stok'],
            ':kategori' => $buku['kategori'] 
        ]);
        $jumlahBerhasil++;
    }

    echo "Selesai! Berhasil memindahkan {$jumlahBerhasil} data buku dari JSON ke database PostgreSQL.";

} catch (PDOException $e) {
    echo "Terjadi kesalahan saat migrasi data: " . $e->getMessage();
}