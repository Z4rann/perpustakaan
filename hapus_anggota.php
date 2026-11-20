<?php
require 'koneksi.php';
require 'auth.php';

// Hanya admin yang boleh menghapus anggota
if ($_SESSION['pengguna']['level'] !== 'admin') {
    redirect(
        'anggota.php',
        'danger',
        'Anda tidak memiliki akses untuk menghapus data.'
    );
}

require 'koneksi.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $koneksi->prepare("DELETE FROM anggota WHERE id_anggota = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    redirect('anggota.php', 'success', 'Data anggota berhasil dihapus.');
} else {
    redirect('anggota.php', 'danger', 'Data anggota tidak ditemukan atau gagal dihapus.');
}
