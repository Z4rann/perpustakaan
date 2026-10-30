<?php
require 'koneksi.php';
// Fungsi bantu upload sampul.
// Mengembalikan nama file, atau null jika tidak ada file yang diupload.
function upload_sampul($file)
{
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    // Pastikan folder sampul tersedia
    if (!is_dir('sampul')) {
        mkdir('sampul', 0775, true);
    }

    // Tambahkan waktu agar nama file tidak mudah bentrok
    $nama_asli = basename($file['name']);
    $ekstensi = strtolower(pathinfo($nama_asli, PATHINFO_EXTENSION));
    $ekstensi_diizinkan = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($ekstensi, $ekstensi_diizinkan, true)) {
        return null;
    }

    $nama_file = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $nama_asli);
    if (move_uploaded_file($file['tmp_name'], 'sampul/' . $nama_file)) {
        return $nama_file;
    }
    return null;
}
// ================= BLOK 1: SIMPAN DATA BARU =================
if (isset($_POST['submit'])) {
    $kode_buku = trim($_POST['kode_buku']);
    $judul = trim($_POST['judul']);
    $pengarang = trim($_POST['pengarang']);
    $penerbit = trim($_POST['penerbit']);
    $tahun_terbit = (int) $_POST['tahun_terbit'];
    $stok = (int) $_POST['stok'];
    // a. Cek duplikat kode buku
    $cek = $koneksi->prepare("SELECT id_buku FROM buku WHERE kode_buku = ?");
    $cek->bind_param("s", $kode_buku);
    $cek->execute();
    $cek->store_result();
    if ($cek->num_rows > 0) {
        $kode = htmlspecialchars($kode_buku);
        redirect(
            'form.php',
            'warning',
            "Kode buku <b>$kode</b> sudah terdaftar. Gunakan kode lain."
        );
    }
    // b. Upload sampul (jika ada)
    $nama_sampul = upload_sampul($_FILES['sampul']) ?? '';
    // c. Simpan dengan prepared statement
    $sql = "INSERT INTO buku
 (kode_buku, judul, pengarang, penerbit, tahun_terbit, stok, sampul)
 VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param(
        "ssssiis",
        $kode_buku,
        $judul,
        $pengarang,
        $penerbit,
        $tahun_terbit,
        $stok,
        $nama_sampul
    );
    if ($stmt->execute()) {
        redirect('buku.php', 'success', 'Data buku berhasil disimpan.');
    } else {
        redirect('form.php', 'danger', 'Gagal menyimpan data.');
    }
}
// ================= BLOK 2: UBAH DATA =================
if (isset($_POST['update'])) {
    $id_buku = (int) $_POST['id_buku'];
    $judul = trim($_POST['judul']);
    $pengarang = trim($_POST['pengarang']);
    $penerbit = trim($_POST['penerbit']);
    $tahun_terbit = (int) $_POST['tahun_terbit'];
    $stok = (int) $_POST['stok'];
    $nama_sampul = upload_sampul($_FILES['sampul']);
    if ($nama_sampul) {
        // Ada sampul baru -> kolom sampul ikut diubah
        $sql = "UPDATE buku SET judul = ?, pengarang = ?, penerbit = ?,
 tahun_terbit = ?, stok = ?, sampul = ?
 WHERE id_buku = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param(
            "sssiisi",
            $judul,
            $pengarang,
            $penerbit,
            $tahun_terbit,
            $stok,
            $nama_sampul,
            $id_buku
        );
    } else {
        // Tidak ada sampul baru -> kolom sampul tidak diubah
        $sql = "UPDATE buku SET judul = ?, pengarang = ?, penerbit = ?,
 tahun_terbit = ?, stok = ?
 WHERE id_buku = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param(
            "sssiii",
            $judul,
            $pengarang,
            $penerbit,
            $tahun_terbit,
            $stok,
            $id_buku
        );
    }
    if ($stmt->execute()) {
        redirect('buku.php', 'success', 'Data buku berhasil diubah.');
    } else {
        redirect("edit.php?id=$id_buku", 'danger', 'Gagal mengubah data.');
    }
}
// ================= BLOK 3: TAMBAH ANGGOTA =================
if (isset($_POST['submit_anggota'])) {
    $nim = trim($_POST['nim']);
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $no_hp = trim($_POST['no_hp']);

    $cek = $koneksi->prepare("SELECT id_anggota FROM anggota WHERE nim = ?");
    $cek->bind_param("s", $nim);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        redirect('form_anggota.php', 'warning', 'NIM sudah terdaftar. Gunakan NIM lain.');
    }

    $stmt = $koneksi->prepare(
        "INSERT INTO anggota (nim, nama, email, no_hp) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("ssss", $nim, $nama, $email, $no_hp);

    if ($stmt->execute()) {
        redirect('anggota.php', 'success', 'Data anggota berhasil disimpan.');
    } else {
        redirect('form_anggota.php', 'danger', 'Gagal menyimpan data anggota.');
    }
}

// ================= BLOK 4: EDIT ANGGOTA =================
if (isset($_POST['update_anggota'])) {
    $id = (int) $_POST['id_anggota'];
    $nim = trim($_POST['nim']);
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $no_hp = trim($_POST['no_hp']);

    $cek = $koneksi->prepare(
        "SELECT id_anggota FROM anggota WHERE nim = ? AND id_anggota != ?"
    );
    $cek->bind_param("si", $nim, $id);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        redirect("edit_anggota.php?id=$id", 'warning', 'NIM sudah digunakan anggota lain.');
    }

    $stmt = $koneksi->prepare(
        "UPDATE anggota SET nim = ?, nama = ?, email = ?, no_hp = ? WHERE id_anggota = ?"
    );
    $stmt->bind_param("ssssi", $nim, $nama, $email, $no_hp, $id);

    if ($stmt->execute()) {
        redirect('anggota.php', 'success', 'Data anggota berhasil diubah.');
    } else {
        redirect("edit_anggota.php?id=$id", 'danger', 'Gagal mengubah data anggota.');
    }
}

// Jika file ini dibuka langsung tanpa mengirim form
header('Location: buku.php');
exit;