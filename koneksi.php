<?php
// Memulai session agar pesan dapat ditampilkan setelah redirect.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Koneksi database
$host = "localhost";
$user = "root";
$password = "";
$database = "perpustakaan_db";

$koneksi = new mysqli($host, $user, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$koneksi->set_charset("utf8mb4");

// Simpan pesan, lalu pindah halaman
function redirect($url, $tipe, $teks)
{
    $_SESSION['pesan'] = [
        'tipe' => $tipe,
        'teks' => $teks
    ];

    header("Location: $url");
    exit;
}
