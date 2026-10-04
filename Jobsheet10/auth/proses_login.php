<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../includes/koneksi.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$maxFailedAttempts = 5;
$lockoutSeconds = 15 * 60;
$_SESSION['login_attempts'] = (array) ($_SESSION['login_attempts'] ?? []);
$attempts = $_SESSION['login_attempts'][$username] ?? ['count' => 0, 'locked_until' => 0];

if ($attempts['locked_until'] > time()) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Terlalu banyak percobaan login. Coba lagi setelah 15 menit.',
    ];
    header('Location: login.php');
    exit;
}

if ($attempts['locked_until'] !== 0) {
    $attempts = ['count' => 0, 'locked_until' => 0];
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    unset($_SESSION['login_attempts'][$username]);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role'];
    if (isset($_POST['remember_me'])) {
        // Buat cookie bertahan 7 hari (7 * 24 * 60 * 60 detik)
        $cookie_time = time() + (7 * 86400);
        setcookie('user_login', $user['username'], $cookie_time, "/", "", false, true); // httponly
    }
    header('Location: ../index.php');
    exit;
}

$attempts['count']++;
if ($attempts['count'] >= $maxFailedAttempts) {
    $attempts['locked_until'] = time() + $lockoutSeconds;
    $message = 'Terlalu banyak percobaan login. Login dibatasi selama 15 menit.';
} elseif ($attempts['count'] >= 3) {
    $remainingAttempts = $maxFailedAttempts - $attempts['count'];
    $message = "Username atau password salah. Percobaan gagal {$attempts['count']} kali, tersisa {$remainingAttempts} kali sebelum login dibatasi 15 menit.";
} else {
    $message = 'Username atau password salah.';
}

$_SESSION['login_attempts'][$username] = $attempts;
$_SESSION['flash'] = ['type' => 'error', 'pesan' => $message];
header('Location: login.php');
exit;
