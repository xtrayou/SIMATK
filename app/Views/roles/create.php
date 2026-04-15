<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="main-content">
    <div class="section-header">
        <h1>Tambah Hak Akses Baru</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= base_url('/admin') ?>">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="<?= base_url('/admin/roles') ?>">Manajemen Akses</a></div>
            <div class="breadcrumb-item">Tambah</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Validation Errors -->
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Form Tambah Hak Akses</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('/admin/roles/store') ?>" method="post">
                            <?= csrf_field() ?>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama Hak Akses <span class="text-danger">*</span></label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" class="form-control" name="nama_role" value="<?= old('nama_role') ?>" required>
                                    <small class="form-text text-muted">Contoh: Editor, Operator, Manager</small>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Deskripsi</label>
                                <div class="col-sm-12 col-md-7">
                                    <textarea class="form-control" name="deskripsi" rows="3"><?= old('deskripsi') ?></textarea>
                                    <small class="form-text text-muted">Deskripsi singkat tentang hak akses ini</small>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Izin Akses <span class="text-danger">*</span></label>
                                <div class="col-sm-12 col-md-7">
                                    <div class="selectgroup selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="permissions[]" value="dashboard" class="selectgroup-input" <?= in_array('dashboard', old('permissions', [])) ? 'checked' : '' ?>>
                                            <span class="selectgroup-button">Dashboard</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="permissions[]" value="users" class="selectgroup-input" <?= in_array('users', old('permissions', [])) ? 'checked' : '' ?>>
                                            <span class="selectgroup-button">Users</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="permissions[]" value="roles" class="selectgroup-input" <?= in_array('roles', old('permissions', [])) ? 'checked' : '' ?>>
                                            <span class="selectgroup-button">Roles</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="permissions[]" value="berita" class="selectgroup-input" <?= in_array('berita', old('permissions', [])) ? 'checked' : '' ?>>
                                            <span class="selectgroup-button">Berita</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="permissions[]" value="galeri" class="selectgroup-input" <?= in_array('galeri', old('permissions', [])) ? 'checked' : '' ?>>
                                            <span class="selectgroup-button">Galeri</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="permissions[]" value="agenda" class="selectgroup-input" <?= in_array('agenda', old('permissions', [])) ? 'checked' : '' ?>>
                                            <span class="selectgroup-button">Agenda</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="permissions[]" value="pendaftaran" class="selectgroup-input" <?= in_array('pendaftaran', old('permissions', [])) ? 'checked' : '' ?>>
                                            <span class="selectgroup-button">Pendaftaran</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="permissions[]" value="settings" class="selectgroup-input" <?= in_array('settings', old('permissions', [])) ? 'checked' : '' ?>>
                                            <span class="selectgroup-button">Settings</span>
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Pilih fitur yang dapat diakses oleh hak akses ini</small>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status <span class="text-danger">*</span></label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="form-control" name="status" required>
                                        <option value="">Pilih Status</option>
                                        <option value="aktif" <?= old('status') === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                        <option value="nonaktif" <?= old('status') === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                <div class="col-sm-12 col-md-7">
                                    <button type="submit" class="btn btn-primary">Simpan Hak Akses</button>
                                    <a href="<?= base_url('/admin/roles') ?>" class="btn btn-secondary">Batal</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>