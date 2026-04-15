<?= $this->extend('layouts/app') ?>

<?php
if (!function_exists('rupiah')) {
    function rupiah($value): string
    {
        return number_format((float) $value, 0, ',', '.');
    }
}

$no = 1;
?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Total: <?= rupiah($totalItem) ?> Kode Barang</h5>
                </div>

                <form method="get" action="<?= current_url() ?>" class="row g-2 mb-3" id="searchFormKodeBarang">
                    <div class="col-md-8">
                        <input
                            type="text"
                            class="form-control"
                            name="q"
                            id="q"
                            placeholder="Cari kode atau nama barang..."
                            value="<?= esc($keyword ?? '') ?>"
                            autocomplete="off">
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                    <div class="col-md-2 d-grid">
                        <a href="<?= current_url() ?>" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="kodeBarangTable">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Kode Barang</th>
                                <th width="70%">Nama Peruntukan / Barang</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$daftarKode): ?>
                                <?= $this->include('kode_barang/partials/empty_state') ?>
                            <?php else: ?>
                                <?php foreach ($daftarKode as $kb): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><span class="badge bg-primary text-white font-monospace"><?= esc($kb['kode']) ?></span></td>
                                        <td class="fw-bold"><?= esc($kb['nama']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/kode-barang.js') ?>"></script>
<?= $this->endSection() ?>