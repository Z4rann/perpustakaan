
<?php
require 'koneksi.php';
require 'auth.php';

$judul_halaman = 'Beranda';
$menu_aktif = 'beranda';

require 'layout/header.php';

$total_judul = $koneksi->query(
    "SELECT COUNT(*) AS total FROM buku"
)->fetch_assoc()['total'];

$total_stok = $koneksi->query(
    "SELECT COALESCE(SUM(stok), 0) AS total FROM buku"
)->fetch_assoc()['total'];

$total_anggota = $koneksi->query(
    "SELECT COUNT(*) AS total FROM anggota"
)->fetch_assoc()['total'];
?>

<h1 class="mb-1">Selamat Datang</h1>

<p class="text-muted mb-4">
    Sistem Informasi Perpustakaan
</p>

<div class="row">

    <div class="col-md-4 mb-3">
        <div class="card text-bg-primary h-100">
            <div class="card-body">
                <h5>Judul Buku</h5>
                <p class="display-5"><?= $total_judul ?></p>
            </div>
            <a href="buku.php"
               class="card-footer text-white text-decoration-none">
                Lihat Data Buku
            </a>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card text-bg-success h-100">
            <div class="card-body">
                <h5>Total Stok Buku</h5>
                <p class="display-5"><?= $total_stok ?></p>
            </div>
            <a href="buku.php"
               class="card-footer text-white text-decoration-none">
                Lihat Data Buku
            </a>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card text-bg-warning h-100">
            <div class="card-body">
                <h5>Anggota</h5>
                <p class="display-5"><?= $total_anggota ?></p>
            </div>
            <a href="anggota.php"
               class="card-footer text-dark text-decoration-none">
                Lihat Data Anggota
            </a>
        </div>
    </div>

</div>

<?php require 'layout/footer.php'; ?>
