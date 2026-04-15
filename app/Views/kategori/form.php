<?= $this->extend('layouts/app') ?>

<?php
$err = static function (string $field) {
    return session("errors.$field");
};

$errClass = static function (string $field) use ($err): string {
    return $err($field) ? 'is-invalid' : '';
};
?>

<?= $this->section('content') ?>

<div class="container my-5">
    <div class="row">

        <div class="col-lg-8">
            <h1 class="mb-4"><?= esc($judulForm) ?></h1>

            <div class="card">
                <div class="card-body">

                    <form action="<?= base_url($actionUrl) ?>" method="POST" id="formKategori">
                        <?= csrf_field() ?>
                        <?php if ($methodSpoof): ?>
                            <input type="hidden" name="_method" value="<?= esc($methodSpoof) ?>">
                        <?php endif ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Nama Kategori <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control <?= $errClass('name') ?>"
                                value="<?= esc($formName) ?>"
                                placeholder="Masukkan nama kategori"
                                required
                                autofocus>
                            <?php if ($err('name')): ?>
                                <div class="invalid-feedback">
                                    <?= esc((string) $err('name')) ?>
                                </div>
                            <?php endif ?>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                class="form-control <?= $errClass('description') ?>"
                                placeholder="Deskripsi kategori (opsional)"><?= esc($formDescription) ?></textarea>
                            <?php if ($err('description')): ?>
                                <div class="invalid-feedback">
                                    <?= esc((string) $err('description')) ?>
                                </div>
                            <?php endif ?>
                        </div>

                        <div class="mb-3 form-check form-switch">
                            <input
                                type="checkbox"
                                id="is_active"
                                name="is_active"
                                value="1"
                                class="form-check-input"
                                <?= $formIsActive ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">
                                Kategori Aktif
                            </label>
                            <small class="text-muted d-block mt-1">
                                Kategori aktif akan ditampilkan dalam pilihan saat membuat barang.
                            </small>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <a href="<?= base_url('/categories') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary" id="btnSimpanKategori">
                                <i class="bi bi-save"></i> <?= esc($submitLabel) ?>
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
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
                            Kategori yang sudah digunakan oleh barang tidak bisa dihapus.
                        </p>
                    </div>
                </div>
            </div>

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
                                <h6 id="preview-name" class="mb-0"><?= esc($previewName) ?></h6>
                                <small class="text-muted"><?= esc($previewModeLabel) ?></small>
                            </div>
                        </div>

                        <p id="preview-description" class="mb-2<?= $previewDescription === null ? ' text-muted italic' : '' ?>">
                            <?= $previewDescription !== null ? esc($previewDescription) : 'Tidak ada deskripsi' ?>
                        </p>

                        <span id="preview-status" class="badge <?= esc($previewStatusClass) ?>">
                            <i class="bi <?= esc($previewStatusIcon) ?>"></i> <?= esc($previewStatusText) ?>
                        </span>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>