<div id="sidebar">
    <div class="sidebar-wrapper">

        <!-- HEADER -->
        <div class="sidebar-header">
            <a href="<?= base_url('dashboard') ?>" class="text-decoration-none">
                <?php
                $sidebarLogo = app_setting('logo', '');
                $sidebarAppName = app_setting('app_name', 'SIMATK');
                $sidebarLogoName = app_setting('logo_name', 'Sistem Inventaris ATK');
                ?>
                <?php if (!empty($sidebarLogo) && file_exists(FCPATH . 'img/' . $sidebarLogo)): ?>
                <h5 class="mb-0 d-flex align-items-center gap-2">
                    <img src="<?= base_url('img/' . esc($sidebarLogo)) ?>" alt="Logo" style="height:28px;width:28px;object-fit:contain;">
                    <?= esc($sidebarAppName) ?>
                </h5>
                <?php else: ?>
                <h5 class="mb-0">
                    <i class="bi bi-box-seam-fill"></i> <?= esc($sidebarAppName) ?>
                </h5>
                <?php endif; ?>
                <small class="text-muted"><?= esc($sidebarLogoName) ?></small>
            </a>
        </div>

        <!-- MENU -->
        <div class="sidebar-menu">
            <ul class="menu">

                <?php
                $role = session()->get('role');
                $currentUri = uri_string();
                $currentReportMode = strtolower((string) (service('request')->getGet('report_mode') ?? 'stock'));
                $isReportStockNow = $currentUri === 'reports/stock' && $currentReportMode !== 'opname';
                $isReportOpname = $currentUri === 'reports/stock' && $currentReportMode === 'opname';
                ?>

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

                    <li class="sidebar-item <?= strpos(uri_string(), 'kode-barang') !== false ? 'active' : '' ?>">
                        <a href="<?= base_url('/kode-barang') ?>" class="sidebar-link">
                            <i class="bi bi-upc-scan"></i>
                            <span>Kode Barang</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= strpos(uri_string(), 'products') !== false ? 'active' : '' ?>">
                        <a href="<?= base_url('/products') ?>" class="sidebar-link">
                            <i class="bi bi-box"></i>
                            <span>Barang</span>
                        </a>
                    </li>

                    <li class="sidebar-title">Stok</li>

                    <li
                        class="sidebar-item <?= strpos(uri_string(), 'stock') !== false && uri_string() != 'stock/history' ? 'active' : '' ?>">
                        <a href="<?= base_url('/stock') ?>" class="sidebar-link">
                            <i class="bi bi-arrow-left-right"></i>
                            <span>Manajemen Stok</span>
                        </a>
                    </li>

                    <li class="sidebar-title">Permintaan</li>

                    <li class="sidebar-item <?= strpos(uri_string(), 'requests') !== false ? 'active' : '' ?>">
                        <a href="<?= base_url('/requests') ?>" class="sidebar-link">
                            <i class="bi bi-check2-square"></i>
                            <span>Kelola Permintaan</span>
                        </a>
                    </li>

                    <li class="sidebar-title">Laporan</li>

                    <li class="sidebar-item <?= $currentUri === 'stock/history' ? 'active' : '' ?>">
                        <a href="<?= base_url('/stock/history') ?>" class="sidebar-link">
                            <i class="bi bi-clock-history"></i>
                            <span>Riwayat Stok</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= $currentUri == 'reports/movements' ? 'active' : '' ?>">
                        <a href="<?= base_url('/reports/movements') ?>" class="sidebar-link">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Pergerakan</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= $isReportStockNow ? 'active' : '' ?>">
                        <a href="<?= base_url('/reports/stock') . '?report_mode=stock' ?>" class="sidebar-link">
                            <i class="bi bi-box-seam"></i>
                            <span>Stok Saat Ini</span>
                        </a>
                    </li>


                <?php endif; ?>

                <!-- SUPERADMIN -->
                <?php if ($role === 'superadmin'): ?>

                    <li class="sidebar-title">Administrasi</li>

                    <li class="sidebar-item <?= strpos(uri_string(), 'users') === 0 ? 'active' : '' ?>">
                        <a href="<?= base_url('/users') ?>" class="sidebar-link">
                            <i class="bi bi-people"></i>
                            <span>Manajemen Pengguna dan Hak Akses</span>
                        </a>
                    </li>

                    <li class="sidebar-title">Laporan</li>

                    <li class="sidebar-item <?= $currentUri === 'stock/history' ? 'active' : '' ?>">
                        <a href="<?= base_url('/stock/history') ?>" class="sidebar-link">
                            <i class="bi bi-clock-history"></i>
                            <span>Riwayat Stok</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= $currentUri == 'reports/movements' ? 'active' : '' ?>">
                        <a href="<?= base_url('/reports/movements') ?>" class="sidebar-link">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Pergerakan</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= $isReportStockNow ? 'active' : '' ?>">
                        <a href="<?= base_url('/reports/stock') . '?report_mode=stock' ?>" class="sidebar-link">
                            <i class="bi bi-box-seam"></i>
                            <span>Stok Saat Ini</span>
                        </a>
                    </li>


                <?php endif; ?>

            </ul>
        </div>

    </div>
</div>