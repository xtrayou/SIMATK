<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container my-5">
    <div class="row">

        <!-- FORM -->
        <div class="col-lg-8">
            <h1 class="mb-4">Edit Kategori</h1>

            <div class="card">
                <div class="card-body">

                    <form action="<?= base_url('/categories/update/' . $kategori['id']) ?>" method="POST" id="formKategori">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="PUT">

                        <!-- Nama -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">
                                Nama Kategori <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="nama"
                                name="name"
                                class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>"
                                value="<?= old('name', $kategori['name'] ?? '') ?>"
                                placeholder="Masukkan nama kategori"
                                required
                                autofocus>
                            <?php if (session('errors.name')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.name') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea
                                id="deskripsi"
                                name="description"
                                rows="4"
                                class="form-control <?= session('errors.description') ? 'is-invalid' : '' ?>"
                                placeholder="Deskripsi kategori (opsional)"><?= old('description', $kategori['description'] ?? '') ?></textarea>
                            <?php if (session('errors.description')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.description') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Aktif -->
                        <div class="mb-3 form-check form-switch">
                            <?php
                            $aktifDefault = (bool) ($kategori['is_active'] ?? true);
                            $aktif = old('is_active', $aktifDefault);
                            ?>
                            <input
                                type="checkbox"
                                id="statusAktif"
                                name="is_active"
                                value="1"
                                class="form-check-input"
                                <?= $aktif ? 'checked' : '' ?>>
                            <label class="form-check-label" for="statusAktif">
                                Kategori Aktif
                            </label>
                            <small class="text-muted d-block mt-1">
                                Kategori aktif akan ditampilkan dalam pilihan saat membuat produk.
                            </small>
                        </div>

                        <!-- Tombol -->
                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <a href="<?= base_url('/categories') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary" id="btnSimpan">
                                <i class="bi bi-save"></i> Perbarui Kategori
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        <!-- PANEL KANAN -->
        <div class="col-lg-4 mt-4 mt-lg-0">

            <!-- Info -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-3">Informasi</h5>

                    <div class="alert alert-info">
                        <h6>Tips Kategori</h6>
                        <ul class="mb-0">
                            <li>Gunakan nama yang jelas dan mudah dipahami</li>
                            <li>Nama kategori harus unik</li>
                            <li>Deskripsi membantu penjelasan lebih detail</li>
                            <li>Kategori nonaktif tidak akan muncul dalam pilihan</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning mb-0">
                        <h6>Perhatian</h6>
                        <p class="mb-0">
                            Kategori yang sudah digunakan oleh produk tidak bisa dihapus.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Preview -->
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Preview</h5>

                    <div class="preview-kategori">
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3">
                                <div class="avatar-isi bg-primary text-white">
                                    <i class="bi bi-collection-fill"></i>
                                </div>
                            </div>
                            <div>
                                <h6 id="previewNama" class="mb-0">
                                    <?= old('name', $kategori['name'] ?? 'Nama Kategori') ?: 'Nama Kategori' ?>
                                </h6>
                                <small class="text-muted">Sedang diedit</small>
                            </div>
                        </div>

                        <p id="previewDeskripsi" class="mb-2">
                            <?php
                            $isiDeskripsi = old('description', $kategori['description'] ?? '');
                            echo trim($isiDeskripsi) !== '' ? esc($isiDeskripsi) : '<em class="text-muted">Tidak ada deskripsi</em>';
                            ?>
                        </p>

                        <span id="previewStatus" class="badge <?= $aktif ? 'bg-success' : 'bg-secondary' ?>">
                            <?= $aktif
                                ? '<i class="bi bi-check-circle"></i> Aktif'
                                : '<i class="bi bi-x-circle"></i> Nonaktif'
                            ?>
                        </span>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

<?= $this->endSection() ?>