<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-9">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-warning border-3 border-bottom">
                <h5 class="mb-0 fw-bold"><i class="bi bi-tools text-warning me-2"></i>Penyesuaian Stok</h5>
                <p class="text-muted small mb-0">Sesuaikan stok sistem dengan jumlah fisik di bku/gudang</p>
            </div>
            <div class="card-body p-4">
                <form action="<?= base_url('/stock/adjustment/store') ?>" method="POST" id="adjustmentForm">
                    <?= csrf_field() ?>

                    <div class="row mb-4">
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">Nomor Referensi</label>
                            <input type="text" class="form-control" name="reference_no" placeholder="Contoh: ADJ-2024-001">
                        </div>
                        <div class="col-md-7">
                            <label class="form-label small fw-bold">Alasan Penyesuaian</label>
                            <input type="text" class="form-control" name="global_notes" placeholder="Contoh: Hasil Stock Opname, Koreksi kesalahan input, dll">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Pilih Barang yang Akan Disesuaikan</label>
                        <div class="input-group">
                            <select id="productPicker" class="form-select select2">
                                <option value="">- Cari Nama Barang -</option>
                                <?php foreach ($daftarBarang as $p): ?>
                                    <option value="<?= $p['id'] ?>" data-name="<?= esc($p['name']) ?>" data-sku="<?= $p['sku'] ?>" data-stock="<?= $p['current_stock'] ?>" data-unit="<?= $p['unit'] ?>">
                                        <?= esc($p['name']) ?> (Stok: <?= $p['current_stock'] ?> <?= $p['unit'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-primary px-4" id="btn-add-item">
                                <i class="bi bi-plus-lg me-1"></i> Tambah
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle d-none" id="adjustmentTable">
                            <thead class="bg-light small">
                                <tr>
                                    <th>Barang</th>
                                    <th width="120" class="text-center">Stok Sistem</th>
                                    <th width="150" class="text-center">Stok Fisik</th>
                                    <th width="120" class="text-center">Selisih</th>
                                    <th width="80" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="rows">
                                <!-- Rows dynamic -->
                            </tbody>
                        </table>
                    </div>

                    <div id="empty-state" class="text-center py-5 border rounded bg-light">
                        <i class="bi bi-clipboard-x fs-1 text-muted"></i>
                        <p class="text-muted mt-2">Belum ada barang dipilih untuk disesuaikan.</p>
                    </div>

                    <div class="d-flex justify-content-end mt-4 pt-3 border-top d-none action-buttons">
                        <button type="submit" class="btn btn-warning px-5 fw-bold" id="btn-submit">
                            TERAPKAN PENYESUAIAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="card shadow-sm border-0 mb-4 bg-light-warning">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Penting!</h6>
                <ul class="small ps-3 mb-0">
                    <li class="mb-2">Gunakan fitur ini hanya jika ada ketidaksesuain stok sistem dengan stok fisik.</li>
                    <li class="mb-2">Sistem akan membuat mutasi tipe <b>ADJUSTMENT</b> secara otomatis.</li>
                    <li>Pastikan angka stok fisik adalah jumlah barang yang ada saat ini.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/stock-adjustment.js') ?>"></script>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/stock-forms.css') ?>">
<?= $this->endSection() ?>