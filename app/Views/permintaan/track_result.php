<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Hasil Lacak Permintaan | SIMATK' ?></title>

    <link rel="icon" href="<?= esc(app_favicon_url(), 'attr') ?>">
    <link rel="shortcut icon" href="<?= esc(app_favicon_url(), 'attr') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap-icons/font/bootstrap-icons.min.css') ?>">

    <link rel="stylesheet" href="<?= base_url('css/track-result.css') ?>">
</head>

<body>
    <div class="container">
        <!-- Brand -->
        <div class="brand text-center mb-3">
            <i class="bi bi-box-seam-fill me-1"></i> SIMATK
        </div>

        <!-- Header Info -->
        <div class="result-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="bi bi-geo-alt me-2"></i> Detail Permintaan Anda
                </h5>
            </div>

            <!-- Status Timeline -->
            <div class="status-timeline">
                <?php
                $steps = [
                    'requested' => ['label' => 'Menunggu', 'show' => true],
                    'approved' => ['label' => 'Disetujui', 'show' => true],
                    'distributed' => ['label' => 'Dikirim', 'show' => true],
                ];
                $currentStatus = $permintaan['status'];
                $statusOrder = ['requested', 'approved', 'distributed', 'cancelled'];
                $currentIndex = array_search($currentStatus, $statusOrder);
                ?>

                <?php foreach ($steps as $status => $step): ?>
                    <?php
                    $stepIndex = array_search($status, $statusOrder);
                    $isCompleted = $stepIndex < $currentIndex;
                    $isActive = $status === $currentStatus;
                    $isCancelled = $currentStatus === 'cancelled';
                    ?>

                    <div class="timeline-step <?= $isCompleted || $isActive ? 'active' : ''; ?> <?= $isCompleted ? 'completed' : ''; ?>">
                        <div class="step-circle">
                            <?php if ($isCompleted): ?>
                                <i class="bi bi-check-lg"></i>
                            <?php else: ?>
                                <?= $stepIndex + 1 ?>
                            <?php endif; ?>
                        </div>
                        <div class="step-label"><?= $step['label'] ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Current Status -->
            <div style="padding: 1.5rem; text-align: center; border-top: 1px solid #e9ecef;">
                <?php
                $badge = $statusBadges[$currentStatus] ?? ['text' => 'Tidak Diketahui', 'color' => 'secondary', 'icon' => 'question-circle'];
                ?>
                <div class="status-badge badge-<?= $badge['color'] ?>">
                    <i class="bi bi-<?= $badge['icon'] ?> me-1"></i> <?= $badge['text'] ?>
                </div>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">
                    <i class="bi bi-calendar3 me-1"></i>
                    Diajukan: <strong><?= formatDate($permintaan['created_at']) ?></strong>
                </p>
            </div>
        </div>

        <!-- Detail Informasi -->
        <div class="result-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="bi bi-info-circle me-2"></i> Informasi Permintaan
                </h5>
            </div>

            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">Kode Resi</span>
                    <span class="info-value"><?= $referenceNo ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nomor Permintaan</span>
                    <span class="info-value">REQ-<?= str_pad((string) ($permintaan['id'] ?? 0), 4, '0', STR_PAD_LEFT) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nama Pemohon</span>
                    <span class="info-value"><?= esc($permintaan['borrower_name']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Unit/Divisi</span>
                    <span class="info-value"><?= esc($permintaan['borrower_unit']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?= esc($permintaan['email']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Permintaan</span>
                    <span class="info-value"><?= formatDate($permintaan['request_date']) ?></span>
                </div>
                <?php if (!empty($permintaan['notes'])): ?>
                    <div class="info-row">
                        <span class="info-label">Catatan</span>
                        <span class="info-value"><?= nl2br(esc((string) ($permintaan['notes'] ?? ''))) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Detail Item -->
        <?php if (!empty($itemPermintaan)): ?>
            <div class="result-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-box-seam me-2"></i> Item Permintaan (<?= count($itemPermintaan) ?>)
                    </h5>
                </div>

                <div class="items-section">
                    <?php foreach ($itemPermintaan as $idx => $data): ?>
                        <div class="item-card">
                            <div class="item-info">
                                <div class="item-name">
                                    <?php if ($data['barang']): ?>
                                        <?= esc($data['barang']['name']) ?>
                                    <?php else: ?>
                                        <em class="text-muted">Barang tidak ditemukan</em>
                                    <?php endif; ?>
                                </div>
                                <div class="item-detail">
                                    <?php if ($data['barang']): ?>
                                        <span class="badge bg-light text-dark"><?= esc($data['barang']['sku']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="item-qty">
                                <?= $data['item']['quantity'] ?> Unit
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Info Box -->
        <div class="alert alert-info border-0 rounded" style="background: #d1ecf1; color: #0c5460; padding: 1rem 1.25rem;">
            <i class="bi bi-info-circle me-2"></i>
            Untuk pertanyaan lebih lanjut, silakan hubungi kami melalui email atau datang langsung ke kantor kami.
        </div>

        <!-- Action Buttons -->
        <div class="btn-group-bottom">
            <a href="<?= base_url('track') ?>" class="btn-track-new">
                <i class="bi bi-search me-1"></i> Lacak Permintaan Lain
            </a>
            <a href="<?= base_url('/') ?>" class="btn-back">
                <i class="bi bi-house me-1"></i> Kembali ke Beranda
            </a>
        </div>

        <p class="mt-4 mb-4 text-center" style="font-size:0.78rem; color:#adb5bd;">
            &copy; <?= date('Y') ?> SIMATK &mdash; Sistem Informasi Manajemen ATK
        </p>
    </div>

    <script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>