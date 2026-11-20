
<?php
require 'koneksi.php';

// Kosongkan semua session
$_SESSION = [];

// Hapus session dari server
session_destroy();

// Buat session baru agar pesan logout dapat disimpan
session_start();

redirect(
    'login.php',
    'success',
    'Anda berhasil logout.'
);
