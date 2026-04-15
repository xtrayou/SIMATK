<?= $this->extend('layouts/app') ?>

<?php
$errorClass = static function (string $field): string {
    return session("errors.$field") ? 'is-invalid' : '';
};

$errorMsg = static function (string $field): string {
    $msg = session("errors.$field");
    return $msg ? '<div class="invalid-feedback">' . esc($msg) . '</div>' : '';
};
?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0">Edit Barang</h4>
                <p class="text-muted mb-0">Memperbarui informasi untuk barang: <strong><?= esc($barang['name']) ?></strong></p>
            </div>
            <div class="card-body p-4">
                <form action="<?= base_url('/products/save') ?>" method="POST" id="productForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $barang['id'] ?>">

                    <?= $this->include('barang/partials/basic_info', [
                        'barang' => $barang,
                        'daftarKategori' => $daftarKategori,
                        'errorClass' => $errorClass,
                        'errorMsg' => $errorMsg,
                    ]) ?>

                    <?= $this->include('barang/partials/harga_stok', [
                        'barang' => $barang,
                        'errorClass' => $errorClass,
                        'errorMsg' => $errorMsg,
                        'showInitialStock' => false,
                    ]) ?>

                    <div class="d-flex justify-content-between pt-3 border-top mt-4">
                        <a href="<?= base_url('/products') ?>" class="btn btn-light px-4">
                            <i class="bi bi-arrow-left me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-warning px-5 text-white" id="btnSubmit">
                            <i class="bi bi-save me-1"></i> Update Barang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Statistics -->
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Ringkasan Status</h5>
            </div>
            <div class="card-body">
                <div class="mb-3 text-center">
                    <?php if ($barang['current_stock'] <= 0): ?>
                        <span class="badge bg-danger p-2 fs-6">Stok Habis</span>
                    <?php elseif ($barang['current_stock'] <= $barang['min_stock']): ?>
                        <span class="badge bg-warning p-2 fs-6">Stok Rendah</span>
                    <?php else: ?>
                        <span class="badge bg-success p-2 fs-6">Stok Tersedia</span>
                    <?php endif; ?>
                </div>
                <hr>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">ID Barang:</span>
                    <span class="fw-bold">#<?= $barang['id'] ?></span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Dibuat Pada:</span>
                    <span><?= date('d/m/Y H:i', strtotime($barang['created_at'])) ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Update Terakhir:</span>
                    <span><?= date('d/m/Y H:i', strtotime($barang['updated_at'])) ?></span>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('/products/show/' . $barang['id']) ?>" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-eye me-1"></i> Lihat Detail
                    </a>
                    <a href="<?= base_url('/stock/history?product=' . $barang['id']) ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-clock-history me-1"></i> Riwayat Mutasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    window.productFormConfig = {
        kodeBarangApiUrl: '<?= base_url('api/kode-barang') ?>',
        submitText: 'Memperbarui...'
    };
</script>
<script src="<?= base_url('js/product-form.js') ?>"></script>
<?= $this->endSection() ?>