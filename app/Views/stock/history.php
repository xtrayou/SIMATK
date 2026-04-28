<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<!-- Report Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-1">
                            <i class="bi bi-clock-history text-primary"></i>
                            Riwayat Stok
                        </h4>
                        <p class="text-muted mb-0">
                            Menampilkan detail setiap transaksi barang masuk, keluar, dan penyesuaian stok
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="btn-group">
                            <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-download me-2"></i>Export
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= base_url('/stock/history/export/excel') . '?' . http_build_query($_GET) ?>">
                                    <i class="bi bi-file-earmark-excel me-2"></i>Excel
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="printHistory(); return false;">
                                    <i class="bi bi-printer me-2"></i>Print
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Tabel -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Daftar Mutasi Stok</h5>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($daftarMutasi)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="historyTable">
                            <thead class="bg-light small">
                                <tr>
                                    <th class="ps-4">Tgl & Waktu</th>
                                    <th>Barang</th>
                                    <th class="text-center">Tipe</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">Stok Sisa</th>
                                    <th>Referensi / Ket</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                <?php foreach ($daftarMutasi as $mut): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold"><?= date('d/m/Y', strtotime($mut['created_at'])) ?></div>
                                            <div class="text-muted" style="font-size: 0.75rem"><?= date('H:i', strtotime($mut['created_at'])) ?></div>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?= esc($mut['product_name']) ?></div>
                                            <code class="text-muted"><?= $mut['product_sku'] ?></code>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($mut['type'] == 'IN'): ?>
                                                <span class="badge bg-success-light text-success border-success px-2 py-1">MASUK</span>
                                            <?php elseif ($mut['type'] == 'OUT'): ?>
                                                <span class="badge bg-danger-light text-danger border-danger px-2 py-1">KELUAR</span>
                                            <?php else: ?>
                                                <span class="badge bg-info-light text-info border-info px-2 py-1">ADJ</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center fw-bold <?= $mut['type'] == 'IN' ? 'text-success' : ($mut['type'] == 'OUT' ? 'text-danger' : 'text-info') ?>">
                                            <?= $mut['type'] == 'IN' ? '+' : ($mut['type'] == 'OUT' ? '-' : '±') ?><?= number_format($mut['quantity']) ?>
                                        </td>
                                        <td class="text-center fw-bold"><?= number_format($mut['current_stock']) ?> <small class="text-muted fw-normal"><?= $mut['unit'] ?></small></td>
                                        <td>
                                            <?php if ($mut['reference_no']): ?>
                                                <div class="badge bg-light text-dark border fw-normal mb-1">Ref: <?= $mut['reference_no'] ?></div>
                                            <?php endif; ?>
                                            <div class="text-muted italic"><?= esc($mut['notes']) ?: '-' ?></div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-clock-history fs-1 text-muted"></i>
                        <p class="text-muted mt-2">Tidak ada data riwayat mutasi.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/stock-history.js') ?>"></script>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/stock-forms.css') ?>">
<?= $this->endSection() ?>