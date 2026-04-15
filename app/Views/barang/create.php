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
                <h4 class="mb-0">Tambah Barang Baru</h4>
                <p class="text-muted mb-0">Lengkapi informasi barang dengan detail yang benar</p>
            </div>
            <div class="card-body p-4">
                <form action="<?= base_url('/products/save') ?>" method="POST" id="productForm">
                    <?= csrf_field() ?>

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
                    ]) ?>

                    <div class="d-flex justify-content-between pt-3 border-top mt-4">
                        <a href="<?= base_url('/products') ?>" class="btn btn-light px-4">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary px-5" id="btnSubmit">
                            <i class="bi bi-save me-1"></i> Simpan Barang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 mb-4 bg-primary text-white">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-lightbulb me-2"></i>Petunjuk</h5>
                <ul class="mb-0 ps-3 small">
                    <li class="mb-2">Gunakan <strong>Kode Barang</strong> yang konsisten agar mudah dalam pencarian.</li>
                    <li class="mb-2"><strong>Stok Minimum</strong> digunakan oleh sistem untuk memberikan notifikasi jika persediaan hampir habis.</li>
                    <li>Harga dapat diupdate sewaktu-waktu jika ada perubahan dari supplier.</li>
                </ul>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">Preview Barang</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="display-4"><i class="bi bi-box-seam text-secondary"></i></div>
                </div>
                <h6 id="preview-name" class="text-center fw-bold mb-1">Nama Barang</h6>
                <p id="preview-category" class="text-center text-muted small mb-3">Kategori</p>
                <hr>
                <div class="row text-center small">
                    <div class="col-6">
                        <p class="mb-0 text-muted">Stok</p>
                        <p id="preview-stock" class="fw-bold fs-5">0</p>
                    </div>
                    <div class="col-6">
                        <p class="mb-0 text-muted">Satuan</p>
                        <p id="preview-unit" class="fw-bold fs-5">Pcs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    window.productFormConfig = {
        kodeBarangApiUrl: '<?= base_url('api/kode-barang') ?>'
    };
</script>
<script src="<?= base_url('js/product-form.js') ?>"></script>
<?= $this->endSection() ?>