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
                        <li><hr class="dropdown-divider"></li>
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