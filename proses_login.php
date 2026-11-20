
<?php
require 'koneksi.php';

// Jika file dibuka langsung tanpa form login
if (!isset($_POST['login'])) {
    header('Location: login.php');
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Cari pengguna berdasarkan email
$stmt = $koneksi->prepare(
    "SELECT * FROM pengguna WHERE email = ?"
);

$stmt->bind_param("s", $email);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

// Cocokkan password dengan hash di database
if ($user && password_verify($password, $user['password'])) {

    // Ganti ID session setelah berhasil login
    session_regenerate_id(true);

    // Simpan data pengguna ke session
    $_SESSION['pengguna'] = [
        'id'    => $user['id_pengguna'],
        'nama'  => $user['nama'],
        'email' => $user['email'],
        'level'  => $user['level']
    ];

    $nama = htmlspecialchars($user['nama']);

    redirect(
        'index.php',
        'success',
        "Selamat datang, $nama!"
    );
}

// Jika email atau password salah
redirect(
    'login.php',
    'danger',
    'Email atau password salah.'
);
