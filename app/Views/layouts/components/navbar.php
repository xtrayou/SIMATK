<header class="mb-3">
    <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container-fluid">

            <!-- Toggle Sidebar -->
            <a href="#" class="me-3" id="sidebarToggle" aria-label="Toggle sidebar">
                <i class="bi bi-list fs-4"></i>
            </a>

            <!-- Title -->
            <span class="navbar-brand mb-0 h1">
                SIMATK
            </span>

            <!-- Right -->
            <ul class="navbar-nav ms-auto align-items-center">

                <!-- Search -->
                <li class="nav-item me-3">
                    <form class="d-flex" method="get" action="<?= base_url('/products') ?>">
                        <input class="form-control form-control-sm"
                            type="search"
                            name="search"
                            placeholder="Cari...">
                    </form>
                </li>

                <!-- Notifications -->
                <li class="nav-item dropdown me-3 nav-notif">
                    <a
                        class="nav-link position-relative"
                        href="#"
                        id="navbarNotifTrigger"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        data-api-latest="<?= base_url('/api/notifications') ?>"
                        data-notif-page="<?= base_url('/notifications') ?>">
                        <i class="bi bi-bell fs-5"></i>
                        <span id="navbarNotifBadge" class="badge rounded-pill bg-danger notif-badge d-none">0</span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-notif" aria-labelledby="navbarNotifTrigger">
                        <div class="notif-header d-flex justify-content-between align-items-center">
                            <strong>Notifikasi</strong>
                            <a href="<?= base_url('/notifications') ?>" class="small text-decoration-none">Lihat semua</a>
                        </div>
                        <div id="navbarNotifList" class="notif-list"></div>
                        <div id="navbarNotifEmpty" class="notif-empty text-muted small">Belum ada notifikasi baru.</div>
                    </div>
                </li>

                <!-- Dropdown User -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <?= session()->get('name') ?? 'User' ?>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="<?= base_url('/profile') ?>">
                                Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= base_url('/settings') ?>">
                                Pengaturan
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="post" action="<?= base_url('/auth/logout') ?>">
                                <?= csrf_field() ?>
                                <button type="submit" class="dropdown-item text-danger">
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>

            </ul>

        </div>
    </nav>
</header>