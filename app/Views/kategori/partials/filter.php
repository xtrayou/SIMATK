<?php
$kataKunci = (string) ($kataKunci ?? '');
$filterStatus = isset($filterStatus) ? (string) $filterStatus : '';
$perHalaman = isset($perHalaman) ? (string) $perHalaman : '10';
$selected = $selected ?? static function ($value, $target): string {
    return (string) $value === (string) $target ? 'selected' : '';
};
?>

<div class="row mb-3">
    <div class="col-md-6">
        <form action="<?= base_url('/categories') ?>" method="GET" id="formCariKategori">
            <div class="input-group">
                <input type="text"
                    id="kataKunci"
                    name="q"
                    value="<?= esc($kataKunci) ?>"
                    class="form-control"
                    placeholder="Cari kategori...">
                <button class="btn btn-outline-secondary">
                    <i class="bi bi-search"></i>
                </button>
                <?php if (!empty($kataKunci)): ?>
                    <a href="<?= base_url('/categories') ?>" class="btn btn-outline-danger">
                        <i class="bi bi-x-circle"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="col-md-6 d-flex justify-content-md-end gap-2 mt-2 mt-md-0">
        <select class="form-select form-select-sm w-auto" id="filterStatus">
            <option value="">Semua Status</option>
            <option value="1" <?= $selected($filterStatus, '1') ?>>Aktif</option>
            <option value="0" <?= $selected($filterStatus, '0') ?>>Nonaktif</option>
        </select>

        <select class="form-select form-select-sm w-auto" id="perHalaman">
            <option value="10" <?= $selected($perHalaman, 10) ?>>10</option>
            <option value="25" <?= $selected($perHalaman, 25) ?>>25</option>
            <option value="50" <?= $selected($perHalaman, 50) ?>>50</option>
        </select>
    </div>
</div>