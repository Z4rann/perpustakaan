<?php
$judul_halaman = $judul_halaman ?? 'Perpustakaan';
$menu_aktif = $menu_aktif ?? '';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($judul_halaman) ?> | Perpustakaan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
        .sampul-buku {
            width: 80px;
            height: 95px;
            object-fit: contain;
        }
        .table-responsive {
            overflow-x: auto;
        }
        /* Navbar tetap terlihat saat halaman di-scroll */
.navbar {
position: sticky;
top: 0;
z-index: 1030;
}

/* Memberi ruang agar footer tidak menutupi isi */
body {
padding-bottom: 64px;
}
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-info">
        <div class="container">
            <a class="navbar-brand" href="buku.php">Perpustakaan</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Buka navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?= $menu_aktif === 'buku' ? 'active' : '' ?>"
                           href="buku.php">Buku</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $menu_aktif === 'anggota' ? 'active' : '' ?>"
                           href="anggota.php">Anggota</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        <?php if (isset($_SESSION['pesan'])): ?>
            <div class="alert alert-<?= htmlspecialchars($_SESSION['pesan']['tipe']) ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['pesan']['teks'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
            <?php unset($_SESSION['pesan']); ?>
        <?php endif; ?>
