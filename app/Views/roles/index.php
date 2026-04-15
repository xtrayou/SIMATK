<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="main-content">
    <div class="section-header">
        <h1>Manajemen Akses</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= base_url('/admin') ?>">Dashboard</a></div>
            <div class="breadcrumb-item">Manajemen Akses</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Alert Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <?= session()->getFlashdata('success') ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <?= session()->getFlashdata('error') ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Daftar Hak Akses</h4>
                        <div class="card-header-action">
                            <a href="<?= base_url('/admin/roles/create') ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Hak Akses
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Nama Hak Akses</th>
                                        <th>Deskripsi</th>
                                        <th>Izin Akses</th>
                                        <th>Status</th>
                                        <th>Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($roles as $role): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td>
                                                <strong><?= esc($role['nama_role']) ?></strong>
                                            </td>
                                            <td><?= esc($role['deskripsi']) ?></td>
                                            <td>
                                                <?php
                                                $permissions = json_decode($role['permissions'], true);
                                                if ($permissions && is_array($permissions)):
                                                ?>
                                                    <div class="d-flex flex-wrap">
                                                        <?php foreach ($permissions as $permission): ?>
                                                            <span class="badge badge-primary mr-1 mb-1"><?= esc($permission) ?></span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">Tidak ada izin akses</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($role['status'] === 'aktif'): ?>
                                                    <span class="badge badge-success">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('d/m/Y H:i', strtotime($role['created_at'])) ?></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                                        Aksi
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="<?= base_url('/admin/roles/edit/' . $role['id']) ?>">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <?php if ($role['nama_role'] !== 'Super Admin'): ?>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item text-danger" href="<?= base_url('/admin/roles/delete/' . $role['id']) ?>"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus hak akses ini?')">
                                                                <i class="fas fa-trash"></i> Hapus
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>