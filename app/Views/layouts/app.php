<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SIMATK' ?></title>
    <link rel="icon" href="<?= esc(app_favicon_url(), 'attr') ?>">
    <link rel="shortcut icon" href="<?= esc(app_favicon_url(), 'attr') ?>">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/simatk-theme.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap-icons/font/bootstrap-icons.min.css') ?>">

    <?= $this->renderSection('styles') ?>
</head>

<body>

    <div id="app">

        <!-- Sidebar Overlay (mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <?= $this->include('layouts/components/sidebar') ?>

        <div id="main">

            <!-- Navbar -->
            <?= $this->include('layouts/components/navbar') ?>

            <!-- Content -->
            <div class="container-fluid p-0">

                <div class="mb-3">
                    <h4><?= $page_title ?? '' ?></h4>
                    <small class="text-muted"><?= $page_subtitle ?? '' ?></small>
                </div>

                <!-- Alert -->
                <?= $this->include('layouts/components/alerts') ?>

                <!-- Main Content -->
                <div class="section">
                    <?= $this->renderSection('content') ?>
                </div>

            </div>

            <!-- Footer -->
            <?= $this->include('layouts/components/footer') ?>

        </div>

    </div>

    <?php if (session()->get('role') === 'superadmin'): ?>
    <!-- ── Floating Appearance Settings Button (Superadmin Only) ── -->
    <button
        id="floatAppearanceBtn"
        class="float-appearance-btn"
        data-bs-toggle="offcanvas"
        data-bs-target="#appearancePanel"
        aria-label="Pengaturan Tampilan"
        title="Pengaturan Tampilan Halaman Publik">
        <i class="bi bi-palette-fill"></i>
    </button>

    <!-- ── Appearance Offcanvas Panel ── -->
    <div class="offcanvas offcanvas-end appearance-panel" tabindex="-1" id="appearancePanel" aria-labelledby="appearancePanelLabel" style="width:420px;">
        <div class="offcanvas-header appearance-panel-header">
            <div class="d-flex align-items-center gap-2">
                <div class="appearance-panel-icon">
                    <i class="bi bi-palette-fill"></i>
                </div>
                <div>
                    <h5 class="mb-0" id="appearancePanelLabel">Pengaturan Tampilan</h5>
                    <small class="opacity-75">Kustomisasi halaman publik</small>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
        </div>
        <div class="offcanvas-body p-0">
            <form action="<?= base_url('/settings/update-appearance') ?>" method="POST" enctype="multipart/form-data" id="formAppearance">
                <?= csrf_field() ?>

                <!-- Section: Identitas -->
                <div class="appearance-section">
                    <div class="appearance-section-title">
                        <i class="bi bi-building me-2"></i>Identitas Aplikasi
                    </div>
                    <div class="appearance-section-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="ap_app_name">Nama Aplikasi</label>
                            <input type="text" class="form-control" id="ap_app_name" name="app_name"
                                   value="<?= esc(app_setting('app_name', 'SIMATK')) ?>" placeholder="SIMATK">
                            <div class="form-text">Muncul di judul halaman dan sidebar</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="ap_institution">Nama Institusi</label>
                            <input type="text" class="form-control" id="ap_institution" name="institution"
                                   value="<?= esc(app_setting('institution', 'Fakultas Ilmu Komputer')) ?>" placeholder="Nama Fakultas / Instansi">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold" for="ap_logo_name">Label Logo (teks bawah ikon)</label>
                            <input type="text" class="form-control" id="ap_logo_name" name="logo_name"
                                   value="<?= esc(app_setting('logo_name', 'Sistem Inventaris ATK')) ?>" placeholder="Sistem Inventaris ATK">
                        </div>
                    </div>
                </div>

                <!-- Section: Logo -->
                <div class="appearance-section">
                    <div class="appearance-section-title">
                        <i class="bi bi-image me-2"></i>Logo Aplikasi
                    </div>
                    <div class="appearance-section-body">
                        <?php
                        $currentLogo = app_setting('logo', '');
                        if (!empty($currentLogo)): ?>
                        <div class="mb-3 d-flex align-items-center gap-3">
                            <img src="<?= base_url('img/' . $currentLogo) ?>" alt="Logo Saat Ini" class="rounded border" style="height:50px;max-width:120px;object-fit:contain;background:#f8f9fa;padding:4px;">
                            <div>
                                <div class="fw-semibold small">Logo saat ini</div>
                                <div class="text-muted" style="font-size:.78rem;"><?= esc($currentLogo) ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="mb-0">
                            <label class="form-label fw-semibold" for="ap_logo">Ganti Logo</label>
                            <input type="file" class="form-control" id="ap_logo" name="logo" accept="image/*">
                            <div class="form-text">Format: JPG, PNG, SVG, WebP &mdash; Maks 2MB</div>
                        </div>
                    </div>
                </div>

                <!-- Section: Hero -->
                <div class="appearance-section">
                    <div class="appearance-section-title">
                        <i class="bi bi-card-image me-2"></i>Hero / Banner Utama
                    </div>
                    <div class="appearance-section-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="ap_hero_title">Judul Hero</label>
                            <input type="text" class="form-control" id="ap_hero_title" name="hero_title"
                                   value="<?= esc(app_setting('hero_title', 'Sistem Inventaris ATK')) ?>" placeholder="Judul halaman utama">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="ap_hero_subtitle">Subjudul Hero</label>
                            <textarea class="form-control" id="ap_hero_subtitle" name="hero_subtitle" rows="2" placeholder="Deskripsi singkat..."><?= esc(app_setting('hero_subtitle', 'Kelola alat tulis kantor dengan mudah, efisien, dan terintegrasi.')) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="ap_hero_label">Aksen Judul (kata kunci)</label>
                            <input type="text" class="form-control" id="ap_hero_label" name="hero_accent"
                                   value="<?= esc(app_setting('hero_accent', 'Inventaris')) ?>" placeholder="Inventaris">
                            <div class="form-text">Kata yang ditonjolkan di judul hero</div>
                        </div>
                        <?php
                        $currentBg = app_setting('hero_bg', '');
                        if (!empty($currentBg) && file_exists(FCPATH . 'img/' . $currentBg)): ?>
                        <div class="mb-3">
                            <div class="fw-semibold small mb-1">Background saat ini</div>
                            <img src="<?= base_url('img/' . $currentBg) ?>" alt="Hero BG" class="rounded border w-100" style="height:80px;object-fit:cover;">
                        </div>
                        <?php endif; ?>
                        <div class="mb-0">
                            <label class="form-label fw-semibold" for="ap_hero_bg">Ganti Background Hero</label>
                            <input type="file" class="form-control" id="ap_hero_bg" name="hero_bg" accept="image/*">
                            <div class="form-text">JPG/PNG/WebP, resolusi minimal 1920×1080 &mdash; Maks 5MB</div>
                        </div>
                    </div>
                </div>

                <!-- Section: Kontak -->
                <div class="appearance-section">
                    <div class="appearance-section-title">
                        <i class="bi bi-info-circle me-2"></i>Info Kontak Publik
                    </div>
                    <div class="appearance-section-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="ap_address">Alamat</label>
                            <input type="text" class="form-control" id="ap_address" name="address"
                                   value="<?= esc(app_setting('address', '')) ?>" placeholder="Jl. Kampus...">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold" for="ap_email">Email Kontak</label>
                            <input type="email" class="form-control" id="ap_email" name="email"
                                   value="<?= esc(app_setting('email', '')) ?>" placeholder="email@domain.ac.id">
                        </div>
                    </div>
                </div>

                <!-- Footer aksi -->
                <div class="appearance-footer">
                    <button type="submit" class="btn btn-appearance-save w-100" id="btnSaveAppearance">
                        <i class="bi bi-floppy-fill me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script src="<?= base_url('assets/libs/jquery/jquery-3.7.1.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/chartjs/chart.umd.min.js') ?>"></script>
    <script src="<?= base_url('js/simatk-theme.js') ?>"></script>

    <?= $this->renderSection('scripts') ?>

</body>

</html>