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

        <!-- Sidebar -->
        <?= $this->include('layouts/components/sidebar') ?>

        <div id="main">

            <!-- Navbar -->
            <?= $this->include('layouts/components/navbar') ?>

            <!-- Content -->
            <div class="container mt-4">

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

    <script src="<?= base_url('assets/libs/jquery/jquery-3.7.1.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/chartjs/chart.umd.min.js') ?>"></script>
    <script src="<?= base_url('js/simatk-theme.js') ?>"></script>

    <?= $this->renderSection('scripts') ?>

</body>

</html>