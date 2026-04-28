<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="row">
    <!-- Form Utama -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-arrow-up-circle text-warning me-2"></i>Form Barang Keluar</h5>
                <p class="text-muted small mb-0">Input pengeluaran stok barang dari inventaris</p>
            </div>
            <div class="card-body p-4">
                <form action="<?= base_url('/stock/out/store') ?>" method="POST" id="stockOutForm">
                    <?= csrf_field() ?>

                    <div class="row mb-4 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-bold small">Nomor Referensi / Surat Jalan</label>
                            <input type="text" class="form-control" name="reference_no" placeholder="Contoh: SJ-001 atau REQ-123">
                        </div>
                        <div class="col-md-7">
                            <label class="form-label fw-bold small">Catatan / Tujuan</label>
                            <input type="text" class="form-control" name="global_notes" placeholder="Contoh: Kirim ke unit kerja, distribusi rutin, dll">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="itemTable">
                            <thead class="bg-light small">
                                <tr>
                                    <th width="45%">Pilih Barang</th>
                                    <th width="15%">Stok</th>
                                    <th width="30%">Jumlah Keluar</th>
                                    <th width="80" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="rows">
                                <tr class="item-row">
                                    <td>
                                        <select class="form-select select-barang" name="movements[0][product_id]" required>
                                            <option value="">- Cari Barang -</option>
                                            <?php foreach ($daftarBarang as $p): ?>
                                                <option value="<?= $p['id'] ?>" data-unit="<?= $p['unit'] ?>" data-stock="<?= $p['current_stock'] ?>"
                                                    <?= $barangTerpilih == $p['id'] ? 'selected' : '' ?>>
                                                    <?= esc($p['name']) ?> (<?= $p['sku'] ?>)
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <span class="stock-info fw-bold">-</span>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control quantity-input" name="movements[0][quantity]" min="1" placeholder="0" required>
                                            <span class="input-group-text small-text unit-label">Pcs</span>
                                        </div>
                                        <small class="text-danger over-stock-msg d-none">Melebihi stok!</small>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-row disabled">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btn-add">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Baris
                        </button>
                        <button type="submit" class="btn btn-warning px-5 fw-bold" id="btn-submit">
                            KELUARKAN BARANG
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4 bg-light">
            <div class="card-body p-4 text-center">
                <h6 class="text-muted fw-bold text-uppercase small mb-3">Total Barang Keluar</h6>
                <h2 class="display-5 fw-bold text-warning mb-0" id="total-qty">0</h2>
                <p class="text-muted mb-0" id="item-count">0 item terpilih</p>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>10 Pengeluaran Terakhir</h6>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($riwayatTerakhir)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($riwayatTerakhir as $mut): ?>
                            <div class="list-group-item p-3 border-0 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-primary small"><?= esc($mut['product_name']) ?></span>
                                    <span class="badge bg-danger small">-<?= number_format($mut['quantity']) ?></span>
                                </div>
                                <div class="d-flex justify-content-between small text-muted">
                                    <span><?= date('d/m/Y H:i', strtotime($mut['created_at'])) ?></span>
                                    <span>Ref: <?= esc($mut['reference_no']) ?: '-' ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="p-4 text-center text-muted italic">Belum ada riwayat keluar.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/stock-out.js') ?>"></script>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/stock-forms.css') ?>">
<?= $this->endSection() ?>