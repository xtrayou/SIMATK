<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMATIK | Inventory System</title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/compiled/css/app.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .section {
            padding: 60px 0;
        }

        .hero {
            background: #3B5BDB;
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .card-simple {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background: white;
            text-align: center;
        }

        .footer {
            background: #2b3a4a;
            color: white;
            padding: 20px 0;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                SIMATIK
            </a>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="container">
            <h1>Sistem Inventaris ATK</h1>
            <p>Kelola stok dan permintaan dengan mudah</p>
        </div>
    </section>

    <!-- FITUR -->
    <section class="section">
        <div class="container text-center">
            <h2 class="mb-4">Fitur Sistem</h2>

            <div class="row">
                <div class="col-md-4">
                    <div class="card-simple">
                        <h5>Manajemen Stok</h5>
                        <p>Kelola barang masuk dan keluar</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-simple">
                        <h5>Laporan</h5>
                        <p>Laporan penggunaan ATK</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-simple">
                        <h5>Permintaan</h5>
                        <p>Ajukan permintaan barang</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FORM -->
    <section class="section">
        <div class="container">
            <h2 class="mb-4 text-center">Form Permintaan</h2>

            <?php if (session('sukses')): ?>
                <div class="alert alert-success"><?= session('sukses') ?></div>
            <?php endif ?>

            <form action="<?= base_url('requests/store') ?>" method="post">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Nama</label>
                        <input type="text" name="borrower_name" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Unit</label>
                        <select name="borrower_unit" class="form-control">
                            <?php foreach ($unitKerja as $u): ?>
                                <option value="<?= $u ?>"><?= $u ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Barang</label>
                    <select name="product_id" class="form-control">
                        <?php foreach ($daftarProduk as $p): ?>
                            <option value="<?= $p['id'] ?>">
                                <?= $p['name'] ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Jumlah</label>
                    <input type="number" name="quantity" class="form-control">
                </div>

                <button class="btn btn-primary">Ajukan</button>
            </form>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer text-center">
        <div class="container">
            <p class="mb-0">
                &copy; <?= date('Y') ?> SIMATIK
            </p>
        </div>
    </footer>

</body>
</html>