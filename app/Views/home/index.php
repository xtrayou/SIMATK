<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIMATK – Sistem Informasi Manajemen ATK Fakultas Ilmu Komputer UNSIKA. Kelola inventaris alat tulis kantor dengan mudah dan efisien.">
    <title>SIMATK | Sistem Informasi Manajemen ATK</title>

    <link rel="icon" href="<?= esc(app_favicon_url(), 'attr') ?>">
    <link rel="shortcut icon" href="<?= esc(app_favicon_url(), 'attr') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap-icons/font/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/aos/aos.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/home.css') ?>">
</head>

<body>

    <?= $this->include('partials/navbar') ?>

    <?= $this->include('home/hero') ?>

    <!-- Section: Form Permintaan ATK -->
    <section class="peminjaman-section" id="permintaan">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">Form <span class="font-accent">Permintaan</span> ATK</h2>
                <p class="section-subtitle">Ajukan permintaan ATK dan Barang Habis Pakai dengan <span class="font-accent">mudah</span></p>
            </div>

            <?= $this->include('home/partials/flash_messages') ?>

            <div class="row g-4">
                <?= $this->include('home/form') ?>
                <?= $this->include('home/info') ?>
            </div>
        </div>
    </section>

    <?= $this->include('home/stats') ?>

    <?= $this->include('home/contact') ?>

    <?= $this->include('partials/footer') ?>

    <?= $this->include('home/partials/modal_resi') ?>
    <?= $this->include('home/partials/modal_cek_status') ?>
    <?= $this->include('home/partials/modal_hasil_status') ?>
    <?= $this->include('home/partials/modal_login') ?>

    <button class="scroll-top" id="tombolScrollAtas">
        <i class="bi bi-rocket-takeoff"></i>
    </button>

    <script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/aos/aos.js') ?>"></script>
    <?= $this->include('home/partials/scripts') ?>

</body>

</html>