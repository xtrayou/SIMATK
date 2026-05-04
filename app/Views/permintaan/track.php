<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Lacak Permintaan ATK | SIMATK' ?></title>

    <link rel="icon" href="<?= esc(app_favicon_url(), 'attr') ?>">
    <link rel="shortcut icon" href="<?= esc(app_favicon_url(), 'attr') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap-icons/font/bootstrap-icons.min.css') ?>">

    <link rel="stylesheet" href="<?= base_url('css/track.css') ?>">
</head>

<body>
    <div class="container-fluid">
        <div class="card track-card mx-auto">
            <!-- Header -->
            <div class="track-header">
                <div class="track-icon">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <h2>Lacak Permintaan</h2>
                <p>Pantau status permintaan ATK Anda</p>
            </div>

            <!-- Body -->
            <div class="track-body">
                <!-- Brand -->
                <div class="brand text-center mb-3">
                    <i class="bi bi-box-seam-fill me-1"></i> SIMATK
                </div>

                <!-- Info Box -->
                <div class="info-box">
                    <i class="bi bi-info-circle"></i>
                    Masukkan nomor referensi dan email untuk melacak status permintaan Anda.
                </div>

                <!-- Pesan Error -->
                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Form -->
                <form action="<?= base_url('track-status') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label class="form-label" for="reference_no">
                            <i class="bi bi-hash me-1"></i> Nomor Referensi
                        </label>
                        <div class="input-icon">
                            <input
                                type="text"
                                class="form-control"
                                id="reference_no"
                                name="reference_no"
                                placeholder="Contoh: 20260410-120305 atau REQ-0001"
                                value="<?= old('reference_no') ?>"
                                required
                                pattern="^([0-9]{8}-[0-9]{6}|REQ-\d+)$">
                            <i class="bi bi-search"></i>
                        </div>
                        <small class="text-muted d-block mt-2">
                            Format: Kode Resi (YYYYMMDD-HHMMSS) atau REQ-####
                        </small>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">
                            <i class="bi bi-envelope me-1"></i> Email
                        </label>
                        <div class="input-icon">
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="Masukkan email Anda"
                                value="<?= old('email') ?>"
                                required>
                            <i class="bi bi-envelope-check"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-track mt-3">
                        <i class="bi bi-search me-2"></i> Lacak Sekarang
                    </button>
                </form>

                <!-- Back Button -->
                <a href="<?= base_url('/') ?>" class="btn-back">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                </a>

                <p class="mt-4 mb-0 text-center" style="font-size:0.78rem; color:#adb5bd;">
                    &copy; <?= date('Y') ?> SIMATK &mdash; Sistem Informasi Manajemen ATK
                </p>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>