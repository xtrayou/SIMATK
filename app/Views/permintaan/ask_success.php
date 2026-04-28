<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Permintaan Terkirim | SIMATK' ?></title>

    <link rel="icon" href="<?= esc(app_favicon_url(), 'attr') ?>">
    <link rel="shortcut icon" href="<?= esc(app_favicon_url(), 'attr') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap-icons/font/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/sweetalert2/sweetalert2.min.css') ?>">

    <link rel="stylesheet" href="<?= base_url('css/ask-success.css') ?>">
</head>

<body>
    <div class="container px-3">
        <div class="card success-card mx-auto fade-in">
            <div class="card-body p-5 text-center">

                <!-- Brand -->
                <div class="brand mb-4">
                    <i class="bi bi-box-seam-fill me-1"></i> SIMATK
                </div>

                <!-- Icon -->
                <div class="success-icon">
                    <i class="bi bi-check-lg"></i>
                </div>

                <!-- Message -->
                <h3 class="fw-bold mb-2">Permintaan Terkirim!</h3>
                <p class="text-muted mb-3">
                    <?php if (!empty($borrower_name)): ?>
                        Terima kasih, <strong><?= esc($borrower_name) ?></strong>.<br>
                    <?php endif; ?>
                    Permintaan ATK Anda telah berhasil diajukan dan akan segera diproses oleh petugas.
                </p>

                <!-- Nomor Referensi -->
                <?php if (!empty($request_id)): ?>
                    <div class="mb-3">
                        <p class="small text-muted mb-1">Nomor Referensi Permintaan</p>
                        <div class="ref-badge">REQ-<?= str_pad($request_id, 4, '0', STR_PAD_LEFT) ?></div>
                    </div>
                <?php endif; ?>

                <!-- Kode Resi Berdasarkan Waktu -->
                <div class="receipt-code">
                    <div class="receipt-label">
                        <i class="bi bi-ticket-perforated me-1"></i> Kode Resi Anda
                    </div>
                    <div class="receipt-value" id="receiptCode">
                        <?= esc((string) ($kode_resi ?? '-')) ?>
                    </div>
                    <button class="copy-btn" onclick="copyReceiptCode()" title="Salin kode resi">
                        <i class="bi bi-clipboard me-1"></i> Salin Kode
                    </button>
                </div>

                <!-- Notifikasi Chat -->
                <div class="chat-notification">
                    <i class="bi bi-chat-dots"></i>
                    <strong>Admin akan segera membalas permohonan Anda via chat atau email</strong>
                    <p class="mb-0 mt-2" style="font-size: 0.9rem;">Harap menyimpan kode resi di atas untuk referensi Anda</p>
                </div>

                <!-- Info -->
                <div class="info-box">
                    <i class="bi bi-clock-history"></i>
                    Permintaan biasanya diproses dalam <strong>1–2 hari kerja</strong>. Status akan terus diperbarui.
                </div>

                <!-- Actions -->
                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center mt-4">
                    <a href="<?= base_url('/track') ?>" class="btn btn-back">
                        <i class="bi bi-search me-1"></i> Lacak Status
                    </a>
                    <a href="<?= base_url('/') ?>" class="btn btn-light">
                        <i class="bi bi-house me-1"></i> Kembali ke Beranda
                    </a>
                </div>

                <p class="mt-4 mb-0" style="font-size:0.78rem; color:#adb5bd;">
                    &copy; <?= date('Y') ?> SIMATK &mdash; Sistem Informasi Manajemen ATK
                </p>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/sweetalert2/sweetalert2.all.min.js') ?>"></script>
    <script src="<?= base_url('js/ask-success.js') ?>"></script>
</body>

</html>