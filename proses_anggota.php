
<?php
require 'koneksi.php';

// TAMBAH ANGGOTA
if (isset($_POST['submit_anggota'])) {

    $nim = trim($_POST['nim']);
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');

    if ($email === '') $email = null;
    if ($no_hp === '') $no_hp = null;

    $cek = $koneksi->prepare(
        "SELECT id_anggota FROM anggota WHERE nim = ?"
    );
    $cek->bind_param("s", $nim);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        redirect('form_anggota.php', 'warning',
            'NIM sudah terdaftar.');
    }

    $stmt = $koneksi->prepare(
        "INSERT INTO anggota (nim, nama, email, no_hp)
         VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("ssss", $nim, $nama, $email, $no_hp);

    if ($stmt->execute()) {
        redirect('anggota.php', 'success',
            'Data anggota berhasil disimpan.');
    } else {
        redirect('form_anggota.php', 'danger',
            'Gagal menyimpan data anggota.');
    }
}

// EDIT ANGGOTA
if (isset($_POST['update_anggota'])) {

    $id = (int) $_POST['id_anggota'];
    $nim = trim($_POST['nim']);
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');

    if ($email === '') $email = null;
    if ($no_hp === '') $no_hp = null;

    $cek = $koneksi->prepare(
        "SELECT id_anggota FROM anggota
         WHERE nim = ? AND id_anggota != ?"
    );
    $cek->bind_param("si", $nim, $id);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        redirect("edit_anggota.php?id=$id", 'warning',
            'NIM sudah digunakan anggota lain.');
    }

    $stmt = $koneksi->prepare(
        "UPDATE anggota
         SET nim = ?, nama = ?, email = ?, no_hp = ?
         WHERE id_anggota = ?"
    );
    $stmt->bind_param("ssssi",
        $nim, $nama, $email, $no_hp, $id
    );

    if ($stmt->execute()) {
        redirect('anggota.php', 'success',
            'Data anggota berhasil diubah.');
    } else {
        redirect("edit_anggota.php?id=$id", 'danger',
            'Gagal mengubah data anggota.');
    }
}

redirect('anggota.php', 'danger', 'Proses tidak valid.');
