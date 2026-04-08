<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SIMATK' ?></title>

    <!-- CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('css/simatk-theme.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script src="<?= base_url('js/simatk-theme.js') ?>"></script>

    <?= $this->renderSection('scripts') ?>

</body>

</html>