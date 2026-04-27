<?php
$barang = is_array($barang ?? null) ? $barang : [];

$errorClass = $errorClass ?? static function (string $field): string {
    return session("errors.$field") ? 'is-invalid' : '';
};

$errorMsg = $errorMsg ?? static function (string $field): string {
    $msg = session("errors.$field");
    return $msg ? '<div class="invalid-feedback">' . esc((string) $msg) . '</div>' : '';
};
?>

<div class="mb-4">
    <h5 class="fw-bold mb-3 border-bottom pb-2 text-primary">
        <i class="bi bi-wallet2 me-2"></i>Harga & Stok
    </h5>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="price" class="form-label fw-bold">Harga <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number"
                    class="form-control <?= $errorClass('price') ?>"
                    id="price"
                    name="price"
                    value="<?= old('price') ?? ($barang['price'] ?? '') ?>"
                    placeholder="0" required>
                <?= $errorMsg('price') ?>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label for="min_stock" class="form-label fw-bold">Stok Minimum <span class="text-danger">*</span></label>
            <input type="number"
                class="form-control <?= $errorClass('min_stock') ?>"
                id="min_stock"
                name="min_stock"
                value="<?= old('min_stock') ?? ($barang['min_stock'] ?? '') ?>"
                required>
            <?= $errorMsg('min_stock') ?>
            <small class="text-muted">Batas minimum untuk notifikasi peringatan stok.</small>
        </div>
    </div>
</div>