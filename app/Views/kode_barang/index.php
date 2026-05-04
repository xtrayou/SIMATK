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
                    <?php if (session()->get('role') === 'admin' || session()->get('role') === 'superadmin'): ?>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kodeBarangModal" onclick="resetForm()">
                            <i class="bi bi-plus-lg"></i> Add Kode Barang
                        </button>
                    <?php endif; ?>
                </div>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

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
                                <th width="55%">Nama Peruntukan / Barang</th>
                                <th width="15%" class="text-center">Aksi</th>
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
                                        <td class="text-center">
                                            <?php if (session()->get('role') === 'admin' || session()->get('role') === 'superadmin'): ?>
                                                <form action="<?= base_url('/kode-barang/delete/' . $kb['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kode barang ini?');">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
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
</div>

<!-- Modal Add Kode Barang -->
<div class="modal fade" id="kodeBarangModal" tabindex="-1" aria-labelledby="kodeBarangModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('/kode-barang/store') ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kodeBarangModalLabel">Tambah Kode Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="kode_id">
                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kode" name="kode" required placeholder="Contoh: ATK-001">
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Peruntukan / Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" required placeholder="Contoh: Kertas HVS">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/kode-barang.js') ?>"></script>
<?= $this->endSection() ?>