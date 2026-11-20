<?php

// 1. Mulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$database = "perpustakaan_db";

$koneksi = new mysqli(
    $host,
    $user,
    $password,
    $database
);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$koneksi->set_charset("utf8mb4");

// 3. Fungsi bantu redirect
if (!function_exists('redirect')) {
    function redirect($url, $tipe, $teks)
    {
        $_SESSION['pesan'] = [
            'tipe' => $tipe,
            'teks' => $teks
        ];

        header("Location: $url");
        exit;
    }
}

?>