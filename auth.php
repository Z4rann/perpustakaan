<?php
// Penjaga halaman
// Jika pengguna belum login, kembalikan ke login

if (!isset($_SESSION['pengguna'])) {
    redirect(
        'login.php',
        'warning',
        'Silakan login terlebih dahulu.'
    );
}
