<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>



<!-- Data Tabel -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Daftar Mutasi Stok</h5>
                <div class="d-flex gap-2">
                    <?php if (session()->get('role') === 'superadmin'): ?>
                        <div class="btn-group me-2">
                            <a href="<?= base_url('/stock/history/export/excel') . '?' . http_build_query($_GET) ?>" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-file-earmark-excel"></i> Excel
                            </a>
                            <a href="<?= base_url('/stock/history/export/pdf') . '?' . http_build_query($_GET) ?>" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-file-earmark-pdf"></i> PDF
                            </a>
                        </div>
                    <?php endif; ?>
                    <a href="<?= base_url('/stock/in') ?>" class="btn btn-sm btn-success">
                        <i class="bi bi-plus-lg me-1"></i> Barang Masuk
                    </a>
                    <a href="<?= base_url('/stock/out') ?>" class="btn btn-sm btn-warning">
                        <i class="bi bi-dash-lg me-1"></i> Barang Keluar
                    </a>
                </div>
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
<script>
    $(document).ready(function() {
        if ($.fn.DataTable && $('#historyTable').length) {
            $('#historyTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                },
                order: [
                    [0, 'desc']
                ]
            });
        }
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .bg-success-light {
        background-color: rgba(25, 135, 84, 0.1);
    }

    .bg-danger-light {
        background-color: rgba(220, 53, 69, 0.1);
    }

    .bg-info-light {
        background-color: rgba(13, 202, 240, 0.1);
    }

    .italic {
        font-style: italic;
    }
</style>
<?= $this->endSection() ?>