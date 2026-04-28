<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Ajukan Permintaan ATK | SIMATK' ?></title>

    <link rel="icon" href="<?= esc(app_favicon_url(), 'attr') ?>">
    <link rel="shortcut icon" href="<?= esc(app_favicon_url(), 'attr') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap-icons/font/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/sweetalert2/sweetalert2.min.css') ?>">

    <link rel="stylesheet" href="<?= base_url('css/ask.css') ?>">
</head>

<body>

    <!-- Header -->
    <div class="ask-header">
        <div class="container">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-box-seam-fill fs-4"></i>
                <span class="brand">SIMA<span style="color:#93b4ff">TIK</span></span>
                <span class="badge bg-white text-primary ms-2" style="font-size:0.75rem;">Sistem Inventaris ATK</span>
            </div>
            <h2 class="fw-bold mb-1">Formulir Permintaan ATK</h2>
            <p class="mb-0 opacity-75">Ajukan kebutuhan alat tulis kantor Anda melalui formulir berikut</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9 col-xl-8">

                <!-- Info Banner -->
                <div class="info-banner mt-4 mb-3">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    Permintaan akan diproses oleh petugas. Pastikan data yang diisi sudah benar sebelum mengirim.
                </div>

                <!-- Alerts -->
                <?php if (session('errors')): ?>
                    <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Terdapat kesalahan:</strong>
                        <ul class="mb-0 mt-1">
                            <?php foreach (session('errors') as $err): ?>
                                <li><?= esc($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm" role="alert">
                        <i class="bi bi-x-circle-fill me-2"></i>
                        <?= esc(session('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Form Card -->
                <div class="card ask-card">
                    <div class="card-body p-4 p-md-5">
                        <form method="post" action="<?= base_url('/ask/store') ?>" id="askForm">
                            <?= csrf_field() ?>

                            <!-- Data Pemohon -->
                            <div class="mb-4">
                                <div class="section-title"><i class="bi bi-person-fill me-2"></i>Informasi Pemohon</div>
                                <div class="row g-3">
                                    <div class="col-md-7">
                                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="borrower_name"
                                            class="form-control <?= session('errors.borrower_name') ? 'is-invalid' : '' ?>"
                                            placeholder="Nama lengkap pemohon"
                                            value="<?= old('borrower_name') ?>" required>
                                        <?php if (session('errors.borrower_name')): ?>
                                            <div class="invalid-feedback"><?= session('errors.borrower_name') ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Unit / Prodi <span class="text-danger">*</span></label>
                                        <input type="text" name="borrower_unit"
                                            class="form-control <?= session('errors.borrower_unit') ? 'is-invalid' : '' ?>"
                                            placeholder="Contoh: Prodi Teknik Informatika"
                                            value="<?= old('borrower_unit') ?>" required>
                                        <?php if (session('errors.borrower_unit')): ?>
                                            <div class="invalid-feedback"><?= session('errors.borrower_unit') ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">NIM / NIP <span class="text-muted fw-normal">(opsional)</span></label>
                                        <input type="text" name="borrower_id_number"
                                            class="form-control"
                                            placeholder="Nomor induk"
                                            value="<?= old('borrower_id_number') ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email"
                                            class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>"
                                            placeholder="nama@kampus.ac.id"
                                            value="<?= old('email') ?>" required>
                                        <?php if (session('errors.email')): ?>
                                            <div class="invalid-feedback"><?= session('errors.email') ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tanggal Permintaan <span class="text-danger">*</span></label>
                                        <input type="date" name="request_date"
                                            class="form-control" required
                                            value="<?= old('request_date', date('Y-m-d')) ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Daftar Barang -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="section-title mb-0"><i class="bi bi-cart-fill me-2"></i>Daftar Barang yang Diminta</div>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="add-item">
                                        <i class="bi bi-plus-lg me-1"></i> Tambah Barang
                                    </button>
                                </div>

                                <?php if (empty($daftarBarang)): ?>
                                    <div class="alert alert-warning border-0 rounded-3">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        Belum ada barang tersedia. Silakan hubungi petugas.
                                    </div>
                                <?php else: ?>
                                    <div id="item-container">
                                        <div class="item-row row g-2 align-items-end">
                                            <div class="col-md-7">
                                                <label class="form-label small">Pilih Barang</label>
                                                <select name="product_id[]" class="form-select select-product" required>
                                                    <option value="">— Pilih Barang —</option>
                                                    <?php foreach ($daftarBarang as $p): ?>
                                                        <option value="<?= $p['id'] ?>"
                                                            data-unit="<?= esc($p['unit']) ?>"
                                                            data-stock="<?= (int) ($p['stock_baik'] ?? $p['current_stock']) ?>">
                                                            <?= esc($p['name']) ?> &nbsp;·&nbsp; Stok: <?= (int) ($p['stock_baik'] ?? $p['current_stock']) ?> <?= esc($p['unit']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label small">Jumlah</label>
                                                <div class="input-group">
                                                    <input type="number" name="quantity[]" class="form-control" min="1" value="1" required>
                                                    <span class="input-group-text unit-label small">Pcs</span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-outline-danger w-100 remove-item disabled">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Catatan -->
                            <div class="mb-4">
                                <div class="section-title"><i class="bi bi-chat-left-text me-2"></i>Keterangan</div>
                                <label class="form-label">Keperluan / Catatan <span class="text-muted fw-normal">(opsional)</span></label>
                                <textarea name="notes" class="form-control" rows="3"
                                    placeholder="Jelaskan tujuan penggunaan atau keterangan tambahan..."><?= old('notes') ?></textarea>
                            </div>

                            <!-- Submit -->
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <p class="footer-note mb-0">
                                    <i class="bi bi-shield-check me-1"></i>
                                    Data Anda hanya digunakan untuk keperluan administrasi ATK.
                                </p>
                                <button type="submit" class="btn btn-primary btn-primary-custom text-white" id="btnSubmit">
                                    <i class="bi bi-send-fill me-2"></i>Kirim Permintaan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Footer -->
                <p class="text-center footer-note mt-4">
                    &copy; <?= date('Y') ?> SIMATK &mdash; Sistem Informasi Manajemen ATK
                </p>

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/sweetalert2/sweetalert2.all.min.js') ?>"></script>
    <script src="<?= base_url('js/ask.js') ?>"></script>
</body>

</html>