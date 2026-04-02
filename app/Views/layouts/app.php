<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SIMATK' ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/compiled/css/app.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Simple Style -->
    <style>
        body {
            background-color: #f5f6fa;
        }

        .section {
            padding: 20px;
        }

        .sidebar-item.active>.sidebar-link {
            background-color: #435ebe;
            color: white;
        }

        .card {
            border-radius: 10px;
            border: 1px solid #eee;
        }

        .alert {
            border-radius: 8px;
        }

        .btn-primary {
            background-color: #435ebe;
            border-color: #435ebe;
        }

        .btn-primary:hover {
            background-color: #364296;
        }
    </style>

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

    <!-- JS (cukup ini saja) -->
    <script src="<?= base_url('assets/compiled/js/app.js') ?>"></script>

    <?= $this->renderSection('scripts') ?>

</body>

</html>