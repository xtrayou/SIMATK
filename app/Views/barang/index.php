<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>


<!-- Tabel Barang -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <div>
                    <h4 class="mb-0">Daftar Barang</h4>
                    <small class="text-muted">Ditemukan <?= number_format($totalItem) ?> item</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('/products/create') ?>" class="btn btn-success">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Barang
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php // Tampilkan notifikasi ketika aksi sebelumnya (tambah, ubah, hapus) berhasil. 
                ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php // Jika data barang ada, render tabel; jika kosong, tampilkan empty state. 
                ?>
                <?php if (!empty($daftarBarang)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="productsTable">
                            <thead class="bg-light text-uppercase small fw-bold">
                                <tr>
                                    <th width="50" class="align-middle border text-center">#</th>
                                    <th class="align-middle border">Informasi Barang</th>
                                    <th class="align-middle border text-center">Kategori</th>
                                    <th class="align-middle border text-end">Harga Estimasi</th>
                                    <th class="align-middle border text-center">Stok Minimum</th>
                                    <th width="130" class="text-center align-middle border">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php // Loop semua barang untuk membentuk setiap baris pada tabel. 
                                ?>
                                <?php foreach ($daftarBarang as $idx => $p): ?>
                                    <tr>
                                        <td class="text-center border-start border-end"><?= $idx + 1 ?></td>
                                        <td class="border-end">
                                            <div class="fw-bold text-dark mb-0"><?= esc($p['name']) ?></div>
                                            <div class="d-flex align-items-center gap-2">
                                                <code class="small text-muted"><?= $p['sku'] ?></code>
                                                <span class="text-muted small">| <?= esc($p['unit'] ?: 'Pcs') ?></span>
                                            </div>
                                        </td>
                                        <td class="text-center border-end">
                                            <span class="badge bg-light text-dark border-0 small"><?= esc($p['category_name']) ?></span>
                                        </td>
                                        <td class="text-end pe-3 border-end">Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                                        <td class="text-center border-end\"><?= number_format((int) ($p['min_stock'] ?? 0)) ?></td>
                                        <td class="text-center border-end">
                                            <div class="btn-group">
                                                <a href="<?= base_url('/products/show/' . $p['id']) ?>" class="btn btn-sm btn-outline-info" title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="<?= base_url('/products/edit/' . $p['id']) ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="<?= base_url('/products/delete/' . $p['id']) ?>" method="post" class="d-inline">
                                                    <?= csrf_field() ?>
                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Hapus"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <img src="<?= base_url('assets/img/empty.svg') ?>" alt="Kosong" style="width: 150px;" class="mb-3 opacity-50">
                        <h5 class="text-muted">Data barang tidak ditemukan</h5>
                        <p class="text-muted">Coba ubah kata kunci pencarian atau filter Anda.</p>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>