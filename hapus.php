<?php
require 'koneksi.php';
require 'auth.php';

// Hanya admin yang boleh menghapus buku
if ($_SESSION['pengguna']['level'] !== 'admin') {
    redirect(
        'buku.php',
        'danger',
        'Anda tidak memiliki akses untuk menghapus data.'
    );
}

require 'koneksi.php';
$id = (int) ($_GET['id'] ?? 0);
$stmt = $koneksi->prepare("DELETE FROM buku WHERE id_buku = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
if ($stmt->affected_rows > 0) {
    redirect('buku.php', 'success', 'Data buku berhasil dihapus.');
} else {
    redirect('buku.php', 'danger', 'Data buku tidak ditemukan atau gagal dihapus.');
}