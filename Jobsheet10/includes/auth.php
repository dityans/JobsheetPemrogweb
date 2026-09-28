<?php
// Guard clause: di-include di baris paling atas setiap halaman yang
// membutuhkan login (sebelum header.php mengeluarkan output apa pun),
// agar header('Location: ...') masih bisa dipanggil.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

function is_admin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function require_admin(): void {
    if (!is_admin()) {
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Anda tidak punya izin untuk aksi ini.'];
        header('Location: list.php');
        exit;
    }
}
