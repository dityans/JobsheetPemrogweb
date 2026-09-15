<?php
// Wajib memanggil session_start() sebelum mengolah sesi
session_start();

// 1. Kosongkan semua variabel array $_SESSION
$_SESSION = array();

// 2. Hapus cookie session dari browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// 3. Hancurkan data session yang tersimpan di server
session_destroy();

// 4. Alihkan kembali ke halaman utama atau daftar
header('Location: index.php');
exit;