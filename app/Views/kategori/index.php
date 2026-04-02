<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container my-5">
    <div class="row">
        <div class="col-12">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Daftar Kategori</h1>
                <a href="<?= base_url('/categories/create') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Kategori
                </a>
            </div>

            <!-- ALERT -->
            <?php if (session('sukses')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= session('sukses') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session('galat')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= session('galat') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- FILTER -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form action="<?= base_url('/categories') ?>" method="GET">
                        <div class="input-group">
                            <input type="text"
                                name="q"
                                value="<?= esc($kataKunci ?? '') ?>"
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
                    <select class="form-select form-select-sm w-auto" id="filterStatus" onchange="terapkanFilter()">
                        <option value="">Semua Status</option>
                        <option value="1" <?= ($filterStatus ?? '') == '1' ? 'selected' : '' ?>>Aktif</option>
                        <option value="0" <?= ($filterStatus ?? '') == '0' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>

                    <select class="form-select form-select-sm w-auto" id="perHalaman" onchange="terapkanFilter()">
                        <option value="10" <?= ($perHalaman ?? 10) == 10 ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= ($perHalaman ?? 10) == 25 ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= ($perHalaman ?? 10) == 50 ? 'selected' : '' ?>>50</option>
                    </select>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Produk</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (empty($daftarKategori)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <?= !empty($kataKunci)
                                        ? 'Tidak ada hasil pencarian'
                                        : 'Belum ada data kategori' ?>
                                </td>
                            </tr>
                        <?php else: ?>

                            <?php foreach ($daftarKategori as $i => $item): ?>
                                <tr>
                                    <td><?= $nomorAwal + $i ?></td>

                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-isi bg-primary text-white">
                                                <i class="bi bi-collection-fill"></i>
                                            </div>
                                            <?= esc($item['name']) ?>
                                        </div>
                                    </td>

                                    <td class="text-muted">
                                        <?= !empty($item['description'])
                                            ? esc(mb_strimwidth($item['description'], 0, 60, '...'))
                                            : 'Tidak ada deskripsi' ?>
                                    </td>

                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?= $item['jumlah_produk'] ?? 0 ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge <?= $item['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= $item['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                        </span>
                                    </td>

                                    <td class="text-muted small">
                                        <?= date('d M Y', strtotime($item['created_at'])) ?>
                                    </td>

                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="<?= base_url('/categories/edit/' . $item['id']) ?>"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <button class="btn btn-sm btn-outline-danger btn-hapus"
                                                data-id="<?= $item['id'] ?>"
                                                data-nama="<?= esc($item['name']) ?>"
                                                data-jumlah="<?= $item['jumlah_produk'] ?? 0 ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <?php if (!empty($paginasi)): ?>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">
                        Menampilkan <?= $nomorAwal ?> – <?= min($nomorAwal + count($daftarKategori) - 1, $totalData) ?>
                        dari <?= $totalData ?>
                    </small>
                    <?= $paginasi ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?= $this->endSection() ?>