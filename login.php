
<?php
require 'koneksi.php';

// Jika sudah login, langsung ke beranda
if (isset($_SESSION['pengguna'])) {
    header('Location: index.php');
    exit;
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Perpustakaan</title>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container" style="max-width: 400px; margin-top: 100px;">

        <div class="card shadow-sm">
            <div class="card-body p-4">

                <h3 class="text-center mb-1">Perpustakaan</h3>

                <p class="text-center text-muted mb-4">
                    Silakan login untuk melanjutkan
                </p>

                <?php if (isset($_SESSION['pesan'])) : ?>
                    <div class="alert alert-<?= htmlspecialchars($_SESSION['pesan']['tipe']) ?>">
                        <?= htmlspecialchars($_SESSION['pesan']['teks']) ?>
                    </div>
                    <?php unset($_SESSION['pesan']); ?>
                <?php endif; ?>

                <form action="proses_login.php" method="post">

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>

                        <input type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>

                        <input type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            required>
                    </div>

                    <div class="d-grid">
                        <input type="submit"
                            value="Login"
                            name="login"
                            class="btn btn-warning">
                    </div>

                </form>

            </div>
        </div>

    </div>

</body>
</html>
