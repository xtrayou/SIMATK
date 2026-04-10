<?= $this->extend('layouts/app') ?>

<?= $this->section('breadcrumb') ?>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Manajemen Hak Akses</li>
</ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php $assignedMap = array_flip(array_map('intval', $assignedPermissionIds ?? [])); ?>
<?php $selectedRoleValue = (string) ($selectedRole ?? 'admin'); ?>

<div class="row">
    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-body d-flex flex-wrap gap-3 align-items-end justify-content-between">
                <div>
                    <h5 class="mb-1"><i class="bi bi-shield-lock me-2"></i>Role Permission Matrix</h5>
                    <p class="text-muted mb-0">Kelola hak akses role tanpa mengubah kode aplikasi.</p>
                </div>
                <form method="GET" action="<?= base_url('/permissions') ?>" class="d-flex gap-2 align-items-end">
                    <div>
                        <label for="role" class="form-label mb-1">Pilih Role</label>
                        <select id="role" name="role" class="form-select">
                            <?php foreach (($roles ?? []) as $role): ?>
                                <?php $roleValue = (string) $role; ?>
                                <option value="<?= esc($roleValue) ?>" <?= ($selectedRoleValue === $roleValue) ? 'selected' : '' ?>>
                                    <?= ucfirst(esc($roleValue)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline-primary">Muat Role</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Permission untuk role: <span class="text-primary"><?= ucfirst(esc($selectedRoleValue)) ?></span></h6>
                <span class="badge bg-light text-dark border">Total group: <?= count($groupedPermissions ?? []) ?></span>
            </div>
            <div class="card-body">
                <?php if (session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= esc((string) session('success')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= esc((string) session('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= base_url('/permissions/update') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="role" value="<?= esc($selectedRoleValue) ?>">

                    <div class="row g-3">
                        <?php foreach (($groupedPermissions ?? []) as $groupName => $permissions): ?>
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 text-uppercase small fw-bold"><?= esc((string) $groupName) ?></h6>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-link p-0 text-decoration-none toggle-group"
                                            data-group="group-<?= esc((string) $groupName) ?>">
                                            Pilih Semua
                                        </button>
                                    </div>
                                    <?php foreach ($permissions as $perm): ?>
                                        <?php $permId = (int) ($perm['id'] ?? 0); ?>
                                        <div class="form-check mb-2">
                                            <input
                                                class="form-check-input permission-checkbox group-<?= esc((string) $groupName) ?>"
                                                type="checkbox"
                                                name="permissions[]"
                                                value="<?= $permId ?>"
                                                id="perm-<?= $permId ?>"
                                                <?= isset($assignedMap[$permId]) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="perm-<?= $permId ?>">
                                                <span class="fw-semibold"><?= esc((string) ($perm['name'] ?? '')) ?></span>
                                                <?php if (!empty($perm['description'])): ?>
                                                    <div class="small text-muted"><?= esc((string) $perm['description']) ?></div>
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Simpan Hak Akses
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.querySelectorAll('.toggle-group').forEach(function(button) {
        button.addEventListener('click', function() {
            const groupClass = button.getAttribute('data-group');
            const checkboxes = Array.from(document.querySelectorAll('.' + groupClass));
            const allChecked = checkboxes.length > 0 && checkboxes.every(function(cb) {
                return cb.checked;
            });

            checkboxes.forEach(function(cb) {
                cb.checked = !allChecked;
            });

            button.textContent = allChecked ? 'Pilih Semua' : 'Batal Pilih';
        });
    });
</script>
<?= $this->endSection() ?>