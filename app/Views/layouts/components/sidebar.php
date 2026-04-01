<div id="sidebar">
    <div class="sidebar-wrapper">

        <!-- HEADER -->
        <div class="sidebar-header">
            <a href="<?= base_url('dashboard') ?>" class="text-decoration-none">
                <h5 class="mb-0">
                    <i class="bi bi-box-seam-fill"></i> SIMATIK
                </h5>
                <small class="text-muted">Sistem Inventaris ATK</small>
            </a>
        </div>

        <!-- MENU -->
        <div class="sidebar-menu">
            <ul class="menu">

                <?php $role = session()->get('role'); ?>

                <!-- DASHBOARD -->
                <li class="sidebar-title">Dashboard</li>
                <li class="sidebar-item <?= uri_string() == 'dashboard' || uri_string() == '' ? 'active' : '' ?>">
                    <a href="<?= base_url('dashboard') ?>" class="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- ADMIN -->
                <?php if ($role === 'admin'): ?>

                    <li class="sidebar-title">Master Data</li>

                    <li class="sidebar-item <?= strpos(uri_string(), 'categories') !== false ? 'active' : '' ?>">
                        <a href="<?= base_url('/categories') ?>" class="sidebar-link">
                            <i class="bi bi-collection"></i>
                            <span>Kategori</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= strpos(uri_string(), 'products') !== false ? 'active' : '' ?>">
                        <a href="<?= base_url('/products') ?>" class="sidebar-link">
                            <i class="bi bi-box"></i>
                            <span>Barang</span>
                        </a>
                    </li>

                    <li class="sidebar-title">Stok</li>

                    <li class="sidebar-item <?= strpos(uri_string(), 'stock') !== false && uri_string() != 'stock/history' ? 'active' : '' ?>">
                        <a href="<?= base_url('/stock') ?>" class="sidebar-link">
                            <i class="bi bi-arrow-left-right"></i>
                            <span>Manajemen Stok</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= uri_string() == 'stock/history' ? 'active' : '' ?>">
                        <a href="<?= base_url('/stock/history') ?>" class="sidebar-link">
                            <i class="bi bi-clock"></i>
                            <span>Riwayat Stok</span>
                        </a>
                    </li>

                    <li class="sidebar-title">Permintaan</li>

                    <li class="sidebar-item <?= strpos(uri_string(), 'requests') !== false ? 'active' : '' ?>">
                        <a href="<?= base_url('/requests') ?>" class="sidebar-link">
                            <i class="bi bi-check2-square"></i>
                            <span>Permintaan ATK</span>
                        </a>
                    </li>

                    <li class="sidebar-title">Laporan</li>

                    <li class="sidebar-item <?= uri_string() == 'reports/stock' ? 'active' : '' ?>">
                        <a href="<?= base_url('/reports/stock') ?>" class="sidebar-link">
                            <i class="bi bi-box"></i>
                            <span>Laporan Stok</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= uri_string() == 'reports/movements' ? 'active' : '' ?>">
                        <a href="<?= base_url('/reports/movements') ?>" class="sidebar-link">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Pergerakan Barang</span>
                        </a>
                    </li>

                <?php endif; ?>

                <!-- SUPERADMIN -->
                <?php if ($role === 'superadmin'): ?>

                    <li class="sidebar-title">Pengaturan</li>

                    <li class="sidebar-item <?= uri_string() == 'users' ? 'active' : '' ?>">
                        <a href="<?= base_url('/users') ?>" class="sidebar-link">
                            <i class="bi bi-shield-lock"></i>
                            <span>Hak Akses</span>
                        </a>
                    </li>

                    <li class="sidebar-title">Laporan</li>

                    <li class="sidebar-item <?= uri_string() == 'reports/stock' ? 'active' : '' ?>">
                        <a href="<?= base_url('/reports/stock') ?>" class="sidebar-link">
                            <i class="bi bi-box"></i>
                            <span>Laporan Stok</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= uri_string() == 'reports/movements' ? 'active' : '' ?>">
                        <a href="<?= base_url('/reports/movements') ?>" class="sidebar-link">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Pergerakan Barang</span>
                        </a>
                    </li>

                <?php endif; ?>

                <!-- USER -->
                <?php if ($role === 'user'): ?>

                    <li class="sidebar-title">Permintaan</li>

                    <li class="sidebar-item <?= uri_string() == 'requests/create' ? 'active' : '' ?>">
                        <a href="<?= base_url('/requests/create') ?>" class="sidebar-link">
                            <i class="bi bi-upload"></i>
                            <span>Ajukan Permintaan</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= strpos(uri_string(), 'requests') !== false && uri_string() != 'requests/create' ? 'active' : '' ?>">
                        <a href="<?= base_url('/requests') ?>" class="sidebar-link">
                            <i class="bi bi-eye"></i>
                            <span>Status Permintaan</span>
                        </a>
                    </li>

                <?php endif; ?>

            </ul>
        </div>

    </div>
</div>