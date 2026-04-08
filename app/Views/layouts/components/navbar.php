<header class="mb-3">
    <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container-fluid">

            <!-- Toggle Sidebar -->
            <a href="#" class="me-3" id="sidebarToggle" aria-label="Toggle sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm.5-4.5a.5.5 0 0 0 0 1h10a.5.5 0 0 0 0-1H3z" />
                </svg>
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M8 16a2 2 0 0 0 1.985-1.75h-3.97A2 2 0 0 0 8 16zm.104-14.995a1 1 0 0 0-.208 0A2.5 2.5 0 0 0 5.5 3.5v.628c0 .987-.27 1.95-.784 2.793L3.615 8.75A1 1 0 0 0 4.47 10.25h7.06a1 1 0 0 0 .854-1.5l-1.1-1.829A5.5 5.5 0 0 1 10.5 4.128V3.5a2.5 2.5 0 0 0-2.396-2.495z" />
                        </svg>
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