<?php
$barang = is_array($barang ?? null) ? $barang : [];
$daftarKategori = is_array($daftarKategori ?? null) ? $daftarKategori : [];

$errorClass = $errorClass ?? static function (string $field): string {
    return session("errors.$field") ? 'is-invalid' : '';
};

$errorMsg = $errorMsg ?? static function (string $field): string {
    $msg = session("errors.$field");
    return $msg ? '<div class="invalid-feedback">' . esc((string) $msg) . '</div>' : '';
};

$selectedCategory = old('category_id') ?? ($barang['category_id'] ?? '');
?>
<div class="mb-4">
    <h5 class="fw-bold mb-3 border-bottom pb-2 text-primary">
        <i class="bi bi-info-circle me-2"></i>Informasi Dasar
    </h5>

    <div class="row">
        <div class="col-md-7 mb-3">
            <label for="name" class="form-label fw-bold">Nama Barang <span class="text-danger">*</span></label>
            <input type="text"
                class="form-control <?= $errorClass('name') ?>"
                id="name"
                name="name"
                value="<?= old('name') ?? ($barang['name'] ?? '') ?>"
                placeholder="Nama barang / barang"
                list="nama_barang_list" autocomplete="off"
                required>
            <?= $errorMsg('name') ?>
        </div>

        <div class="col-md-5 mb-3">
            <label for="category_id" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
            <select class="form-select <?= $errorClass('category_id') ?>"
                id="category_id"
                name="category_id"
                required>
                <option value="" disabled <?= $selectedCategory === '' ? 'selected' : '' ?>>Pilih Kategori</option>
                <?php foreach ($daftarKategori as $kat): ?>
                    <option value="<?= $kat['id'] ?>"
                        <?= (string) $selectedCategory === (string) $kat['id'] ? 'selected' : '' ?>>
                        <?= esc($kat['name']) ?>
                    </option>
                <?php endforeach ?>
            </select>
            <?= $errorMsg('category_id') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="sku" class="form-label fw-bold">Kode Barang <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="text"
                    class="form-control <?= $errorClass('sku') ?>"
                    id="sku"
                    name="sku"
                    value="<?= old('sku') ?? ($barang['sku'] ?? '') ?>"
                    placeholder="Ketik/Pilih Kode Barang..."
                    list="kode_barang_list" autocomplete="off"
                    required>
                <?= $errorMsg('sku') ?>
            </div>
            <small class="text-muted">Gunakan kode unik untuk identifikasi</small>
        </div>

        <div class="col-md-6 mb-3">
            <label for="unit" class="form-label fw-bold">Satuan <span class="text-danger">*</span></label>
            <?php $selectedUnit = old('unit') ?? ($barang['unit'] ?? 'Pcs'); ?>
            <select class="form-select" id="unit" name="unit" required>
                <option value="Pcs" <?= $selectedUnit === 'Pcs' ? 'selected' : '' ?>>Pcs</option>
                <option value="Box" <?= $selectedUnit === 'Box' ? 'selected' : '' ?>>Box</option>
                <option value="Pack" <?= $selectedUnit === 'Pack' ? 'selected' : '' ?>>Pack</option>
                <option value="Lusin" <?= $selectedUnit === 'Lusin' ? 'selected' : '' ?>>Lusin</option>
                <option value="Kg" <?= $selectedUnit === 'Kg' ? 'selected' : '' ?>>Kilogram</option>
                <option value="Liter" <?= $selectedUnit === 'Liter' ? 'selected' : '' ?>>Liter</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <label for="description" class="form-label fw-bold">Keterangan / Deskripsi</label>
            <textarea class="form-control <?= $errorClass('description') ?>"
                id="description"
                name="description"
                rows="3"
                placeholder="Opsional: tambahkan keterangan lengkap"><?= old('description') ?? ($barang['description'] ?? '') ?></textarea>
            <?= $errorMsg('description') ?>
        </div>
    </div>
</div>