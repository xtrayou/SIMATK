<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMATK | Sistem Informasi Manajemen ATK - Fakultas Ilmu Komputer</title>

    <link rel="icon" href="<?= esc(app_favicon_url(), 'attr') ?>">
    <link rel="shortcut icon" href="<?= esc(app_favicon_url(), 'attr') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap-icons/font/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/aos/aos.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/home.css') ?>">
</head>

<body>
    <!-- Navbar -->
    <?= $this->include('partials/navbar') ?>

    <?php
    $daftarBarang = $daftarBarang ?? [];
    $daftarBarangTersedia = $daftarBarangTersedia ?? [];
    $daftarKategori = $daftarKategori ?? [];
    $unitKerja = $unitKerja ?? [];
    ?>

    <!-- Hero -->
    <section class="hero-section" id="beranda">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-8 col-xl-7" data-aos="fade-up" data-aos-duration="1000">
                    <div class="hero-content text-center">
                        <h1 class="hero-title">
                            Sistem <span class="font-accent text-highlight">Inventaris</span> ATK<br>
                            <span style="color:rgba(255,255,255,0.9);">Fakultas <span class="font-accent">Ilmu
                                    Komputer</span></span>
                        </h1>
                        <p class="hero-subtitle">
                            Kelola alat tulis kantor dengan <span class="font-accent">mudah, efisien,</span> dan
                            terintegrasi.<br>
                            Pantau stok, lacak penggunaan, dan buat laporan secara <span
                                class="font-accent">real-time.</span>
                        </p>
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <a href="#permintaan" class="btn btn-hero btn-hero-primary">
                                <i class="bi bi-rocket-takeoff me-2"></i>Klik untuk membuat permintaan
                            </a>
                            <button type="button" class="btn btn-hero btn-hero-outline" data-bs-toggle="modal"
                                data-bs-target="#modalPanduanPengisian">
                                <i class="bi bi-journal-text me-2"></i>Panduan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php // Barang tersedia (hanya stok > 0, ditampilkan bergulir) 
    ?>
    <!-- Barang Tersedia -->
    <section class="barang-tersedia-section" id="barang-tersedia">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="section-title">Barang <span class="font-accent">Tersedia</span></h2>
                <p class="section-subtitle mb-4">Daftar barang yang dapat dipinjam saat ini</p>
            </div>
            <?php // Panduan pengisian ditampilkan lewat modal 
            ?>
            <?php
            $daftarBarangTersedia = $daftarBarangTersedia ?? [];
            $jumlahBarangTersedia = count($daftarBarangTersedia);
            $loopBarangTersedia = $jumlahBarangTersedia > 1
                ? array_merge($daftarBarangTersedia, $daftarBarangTersedia)
                : $daftarBarangTersedia;
            $kelasTrack = $jumlahBarangTersedia > 1 ? 'barang-track' : 'barang-track barang-track-static';
            ?>
            <?php if (empty($daftarBarangTersedia)): ?>
                <div class="text-center">
                    <p class="text-muted"><i class="bi bi-box-seam me-2"></i>Saat ini tidak ada barang yang tersedia.</p>
                </div>
            <?php else: ?>
                <div class="barang-tersedia-scroll">
                    <div class="<?= esc($kelasTrack) ?>">
                        <?php foreach ($loopBarangTersedia as $barang): ?>
                            <?php
                            $stokBarang = (int) ($barang['current_stock'] ?? 0);
                            $satuanBarang = (string) ($barang['unit'] ?? '');
                            $stokTerbatas = $stokBarang <= 10;
                            $labelStok = $stokTerbatas ? 'Terbatas' : 'Tersedia';
                            $kelasBadge = $stokTerbatas ? 'badge-terbatas' : 'badge-tersedia';
                            ?>
                            <div class="barang-item">
                                <div class="card h-100 shadow-sm border-0 barang-card">
                                    <div class="card-body text-center d-flex flex-column justify-content-center p-4">
                                        <h6 class="card-title fw-bold text-dark mb-3">
                                            <?= esc((string) ($barang['name'] ?? '')) ?></h6>
                                        <div>
                                            <span class="badge barang-badge-status <?= esc($kelasBadge) ?>">
                                                <?= esc($labelStok) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Form Permintaan ATK -->
    <section class="peminjaman-section" id="permintaan">
        <div class="container">
            <div
                class="section-heading d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                <div class="text-center text-md-start">
                    <h2 class="section-title">Form <span class="font-accent">Permintaan</span> ATK</h2>
                    <p class="section-subtitle mb-0">Ajukan permintaan ATK dan Barang Habis Pakai dengan <span
                            class="font-accent">mudah</span></p>
                </div>
                <div class="text-center text-md-end">
                    <button type="button" class="btn btn-outline-primary btn-panduan" data-bs-toggle="modal"
                        data-bs-target="#modalPanduanPengisian">
                        <i class="bi bi-journal-text me-2"></i>Panduan Pengisian
                    </button>
                </div>
            </div>

            <?php if (session('sukses')): ?>
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i> <?= session('sukses') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif ?>

            <?php if (session('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <ul class="mb-0">
                        <?php foreach (session('errors') as $err): ?>
                            <li><?= esc((string) $err) ?></li>
                        <?php endforeach ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif ?>

            <div class="row g-4">
                <!-- Form -->
                <div class="col-lg-7">
                    <div class="peminjaman-card">
                        <form class="peminjaman-form" action="<?= base_url('ask/store') ?>" method="post"
                            id="formPermintaan">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_redirect" value="<?= current_url() . '#permintaan' ?>">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="namaPemohon" class="form-label">
                                        <i class="bi bi-person me-1"></i>Nama Pemohon <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="namaPemohon" name="borrower_name"
                                        value="<?= old('borrower_name') ?>" placeholder="Masukkan nama lengkap"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="unitKerja" class="form-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            fill="currentColor" viewBox="0 0 16 16" class="me-1" aria-hidden="true"
                                            style="margin-top:-2px;">
                                            <path
                                                d="M6.5 15V1.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 .5.5V15h-2v-2h-2v2h-2zm-5 0V7.5a.5.5 0 0 1 .5-.5h3V15h-3zM2 8v1h2V8H2zm0 2v1h2v-1H2zm0 2v1h2v-1H2zm6-9v1h1V3H8zm2 0v1h1V3h-1zM8 5v1h1V5H8zm2 0v1h1V5h-1zM8 7v1h1V7H8zm2 0v1h1V7h-1zM8 9v1h1V9H8zm2 0v1h1V9h-1z" />
                                        </svg>Unit Kerja / Prodi <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="unitKerja" name="borrower_unit" required>
                                        <option value="">Pilih Unit Kerja</option>
                                        <?php foreach ($unitKerja as $unit): ?>
                                            <option value="<?= esc((string) $unit) ?>" <?= old('borrower_unit') == $unit ? 'selected' : '' ?>>
                                                <?= esc((string) $unit) ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="emailPemohon" class="form-label">
                                        <i class="bi bi-envelope me-1"></i>Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control" id="emailPemohon" name="email"
                                        value="<?= old('email') ?>" placeholder="nama@fasilkom.ac.id" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nimNipPemohon" class="form-label">
                                        <i class="bi bi-card-text me-1"></i>NIM / NIP
                                    </label>
                                    <input type="text" class="form-control" id="nimNipPemohon"
                                        name="borrower_identifier" value="<?= old('borrower_identifier') ?>"
                                        placeholder="Opsional">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="filterKategori" class="form-label">
                                    <i class="bi bi-tag me-1"></i>Filter Kategori Barang
                                </label>
                                <select class="form-select" id="filterKategori">
                                    <option value="">Tampilkan Semua</option>
                                    <?php foreach ($daftarKategori as $kat): ?>
                                        <option value="<?= $kat['id'] ?>"><?= esc((string) ($kat['name'] ?? '')) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="barangDiminta" class="form-label">
                                    <i class="bi bi-box-seam me-1"></i>Barang yang Diminta <span
                                        class="text-danger">*</span>
                                </label>
                                <?php
                                $oldProductId = (string) old('product_id');
                                $oldProductName = '';
                                foreach ($daftarBarang as $barang) {
                                    if ((string) $barang['id'] === $oldProductId) {
                                        $oldProductName = $barang['name'];
                                        break;
                                    }
                                }
                                ?>
                                <input type="text" class="form-control" id="barangDiminta"
                                    list="daftarBarangAutocomplete" value="<?= esc((string) $oldProductName) ?>"
                                    placeholder="Ketik nama barang untuk cari otomatis..." autocomplete="off" required>
                                <input type="hidden" id="barangDimintaId" name="product_id"
                                    value="<?= esc($oldProductId) ?>">
                                <datalist id="daftarBarangAutocomplete">
                                    <?php foreach ($daftarBarang as $barang): ?>
                                        <?php
                                        $stokBarang = (int) ($barang['current_stock'] ?? 0);
                                        if ($stokBarang <= 0) {
                                            $statusStokLabel = '🔴 Perlu pengadaan';
                                        } elseif ($stokBarang <= 10) {
                                            $statusStokLabel = '🟡 Terbatas';
                                        } else {
                                            $statusStokLabel = '🟢 Tersedia';
                                        }
                                        ?>
                                        <option value="<?= esc((string) ($barang['name'] ?? '')) ?>"
                                            data-id="<?= $barang['id'] ?>" data-kategori="<?= $barang['category_id'] ?>"
                                            data-stok="<?= $barang['current_stock'] ?>"
                                            data-satuan="<?= esc((string) ($barang['unit'] ?? '')) ?>"
                                            data-tersedia="<?= $barang['current_stock'] > 0 ? '1' : '0' ?>"
                                            label="Status: <?= esc($statusStokLabel) ?>">
                                        </option>
                                    <?php endforeach ?>
                                </datalist>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="jumlahDiminta" class="form-label">
                                        <i class="bi bi-123 me-1"></i>Jumlah <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                        <input type="number" class="form-control" id="jumlahDiminta" name="quantity"
                                            value="<?= old('quantity', 1) ?>" min="1" placeholder="0" autocomplete="off"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggalPermintaan" class="form-label">
                                        <i class="bi bi-calendar me-1"></i>Tanggal Permintaan <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="tanggalPermintaan" name="request_date"
                                        value="<?= old('request_date', date('Y-m-d')) ?>" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="keteranganPermintaan" class="form-label">
                                    <i class="bi bi-card-text me-1"></i>Keperluan / Keterangan
                                </label>
                                <textarea class="form-control" id="keteranganPermintaan" name="notes" rows="3"
                                    placeholder="Jelaskan keperluan permintaan barang..."><?= old('notes') ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-submit w-100" id="btnAjukan">
                                <i class="bi bi-send me-2"></i>Ajukan Permintaan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Panel info -->
                <div class="col-lg-5">
                    <div class="peminjaman-info">
                        <h4><i class="bi bi-info-circle me-2"></i>Informasi Permintaan</h4>
                        <div class="peminjaman-info-item">
                            <i class="bi bi-check-circle"></i>
                            <div>
                                <h6>Barang yang Tersedia</h6>
                                <p>Permintaan dapat diajukan sesuai kebutuhan, termasuk saat stok sedang terbatas.</p>
                            </div>
                        </div>
                        <div class="peminjaman-info-item">
                            <i class="bi bi-clock-history"></i>
                            <div>
                                <h6>Proses Persetujuan</h6>
                                <p>Permintaan diproses dalam 1×24 jam kerja setelah pengajuan.</p>
                            </div>
                        </div>
                        <div class="peminjaman-info-item">
                            <i class="bi bi-truck"></i>
                            <div>
                                <h6>Distribusi Barang</h6>
                                <p>Setelah disetujui, barang didistribusikan ke unit kerja pemohon.</p>
                            </div>
                        </div>
                        <div class="peminjaman-info-item">
                            <i class="bi bi-question-circle"></i>
                            <div>
                                <h6>Kontak Admin</h6>
                                <p>Hubungi Admin di <a href="https://wa.me/6287896314494" target="_blank"
                                        class="text-decoration-none fw-bold" style="color:var(--primary-color);">+62
                                        878-9631-4494</a></p>
                                <p class="mt-1 small text-muted">Akses layanan student services di <a
                                        href="https://unsika.link/layananfasilkom" target="_blank">Layanan Fasilkom</a>
                                </p>
                            </div>
                        </div>
                        <div class="card border-0 shadow-sm mt-4">
                            <div class="card-body text-center">
                                <h6 class="mb-2"><i class="bi bi-search me-2"></i>Cek Status Permintaan</h6>
                                <p class="small text-muted mb-3">Lacak status cukup dengan kode resi, langsung dari
                                    beranda.</p>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalCekStatus">
                                    <i class="bi bi-search me-1"></i>Buka Cek Status
                                </button>
                            </div>
                        </div>
                        <hr style="opacity:0.2;">
                        <h6 class="mb-3"><i class="bi bi-tags me-2"></i>Kategori Tersedia:</h6>
                        <?php
                        $kategoriPreview = array_slice($daftarKategori, 0, 12);
                        $kategoriLainnya = array_slice($daftarKategori, 12);
                        ?>
                        <div class="kategori-preview">
                            <?php foreach ($kategoriPreview as $kat): ?>
                                <span class="barang-badge"
                                    title="<?= esc((string) ($kat['name'] ?? '')) ?>"><?= esc((string) ($kat['name'] ?? '')) ?></span>
                            <?php endforeach ?>
                        </div>

                        <?php if (!empty($kategoriLainnya)): ?>
                            <button class="btn btn-kategori-toggle" type="button" data-bs-toggle="collapse"
                                data-bs-target="#kategoriLainnyaCollapse" aria-expanded="false"
                                aria-controls="kategoriLainnyaCollapse">
                                Lihat <?= count($kategoriLainnya) ?> kategori lainnya
                            </button>
                            <div class="collapse" id="kategoriLainnyaCollapse">
                                <div class="kategori-all-wrap">
                                    <div class="kategori-all">
                                        <?php foreach ($kategoriLainnya as $kat): ?>
                                            <span class="barang-badge"
                                                title="<?= esc((string) ($kat['name'] ?? '')) ?>"><?= esc((string) ($kat['name'] ?? '')) ?></span>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats-section">
        <div class="container">
            <div class="row" data-aos="fade-up">
                <div class="col-md-4 col-6">
                    <div class="stat-item"><span class="stat-number"
                            data-count="<?= $stats['total_barang'] ?? 0 ?>">0</span><span class="stat-label">Jenis
                            Barang</span></div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="stat-item"><span class="stat-number"
                            data-count="<?= $stats['total_kategori'] ?? 0 ?>">0</span><span
                            class="stat-label">Kategori</span></div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="stat-item"><span class="stat-number"
                            data-count="<?= $stats['jam_operasi'] ?? 8 ?>">0</span><span class="stat-label">Jam
                            Operasional</span></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak -->
    <section class="contact-section" id="kontak">
        <div class="container">
            <div class="text-center" data-aos="fade-up">
                <h2 class="section-title">Hubungi <span class="font-accent">Kami</span></h2>
                <p class="section-subtitle">Ada pertanyaan? <span class="font-accent">Jangan ragu</span> untuk
                    menghubungi kami</p>
            </div>
            <div class="row g-3 justify-content-center" data-aos="fade-up" data-aos-delay="100">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="bi bi-geo-alt"></i></div>
                        <h6>Alamat</h6>
                        <p>Fakultas Ilmu Komputer<br>Universitas Singaperbangsa Karawang</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="bi bi-telephone"></i></div>
                        <h6>Telepon</h6>
                        <p>+62 878-9631-4494</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="bi bi-envelope"></i></div>
                        <h6>Email</h6>
                        <p>fasilkom@unsika.ac.id</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="bi bi-clock"></i></div>
                        <h6>Jam Operasional</h6>
                        <p>Sen–Jum: 08:00–16:00<br>Sabtu: 08:00–12:00</p>
                    </div>
                </div>
            </div>

            <!-- Social Bar -->
            <div class="social-bar" data-aos="fade-up" data-aos-delay="200">
                <a href="https://www.instagram.com/fasilkomunsika" target="_blank" class="sb-ig"
                    title="@fasilkomunsika">
                    <i class="bi bi-instagram"></i>
                </a>
                <a href="https://www.facebook.com/fasilkom.unsika/" target="_blank" class="sb-fb"
                    title="Facebook Fasilkom">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="https://cs.unsika.ac.id/" target="_blank" class="sb-web" title="cs.unsika.ac.id">
                    <i class="bi bi-globe"></i>
                </a>
                <a href="https://unsika.link/layananfasilkom" target="_blank" class="sb-link" title="Layanan Mahasiswa">
                    <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?= $this->include('partials/footer') ?>

    <!-- Modal Panduan Pengisian -->
    <div class="modal fade" id="modalPanduanPengisian" tabindex="-1" aria-labelledby="judulModalPanduan"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content panduan-modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="judulModalPanduan">
                        <i class="bi bi-journal-text me-2" style="color:var(--primary-color);"></i>Panduan Pengisian
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-0" style="overflow: hidden;">
                    <style>
                        #carouselPanduan {
                            width: 100%;
                            overflow: hidden;
                        }

                        #carouselPanduan .carousel-item {
                            min-height: 550px;
                            backface-visibility: hidden;
                            -webkit-backface-visibility: hidden;
                            perspective: 1000px;
                        }

                        .panduan-image-wrap {
                            height: 400px;
                            width: 100%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            background: #f8f9fa;
                            border-radius: 12px;
                            overflow: hidden;
                            margin-bottom: 10px;
                            border: 1px solid rgba(0, 0, 0, 0.05);
                        }

                        .panduan-image {
                            max-height: 100%;
                            max-width: 100%;
                            display: block;
                            object-fit: contain;
                        }

                        #carouselPanduan h6 {
                            font-size: 1.4rem;
                        }

                        #carouselPanduan .panduan-modal-list li {
                            font-size: 1.1rem;
                        }

                        #carouselPanduan .text-muted {
                            font-size: 1rem !important;
                        }

                        /* Mencegah getaran saat transisi */
                        .carousel-inner {
                            overflow: hidden;
                            width: 100%;
                            position: relative;
                        }

                        .btn-nav-panduan {
                            width: 55px !important;
                            height: 55px !important;
                            background: rgba(255, 255, 255, 0.9) !important;
                            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                        }
                    </style>
                    <div id="carouselPanduan" class="carousel slide" data-bs-interval="false">
                        <div class="carousel-indicators mb-0" style="bottom: 10px;">
                            <button type="button" data-bs-target="#carouselPanduan" data-bs-slide-to="0"
                                class="active bg-secondary" style="width: 30px;"></button>
                            <button type="button" data-bs-target="#carouselPanduan" data-bs-slide-to="1"
                                class="bg-secondary" style="width: 30px;"></button>
                            <button type="button" data-bs-target="#carouselPanduan" data-bs-slide-to="2"
                                class="bg-secondary" style="width: 30px;"></button>
                            <button type="button" data-bs-target="#carouselPanduan" data-bs-slide-to="3"
                                class="bg-secondary" style="width: 30px;"></button>
                        </div>
                        <div class="carousel-inner p-4 pb-5">
                            <!-- Slide 1: Pengisian -->
                            <div class="carousel-item active">
                                <div class="row g-4 align-items-start">
                                    <div class="col-lg-6">
                                        <div class="panduan-image-wrap">
                                            <img src="<?= base_url('img/panduan.png') ?>" alt="Contoh form permintaan"
                                                class="panduan-image img-fluid">
                                        </div>
                                        <p class="small text-muted mt-2 mb-0 text-center">Tampilan form permintaan.</p>
                                    </div>
                                    <div class="col-lg-6">
                                        <h6 class="fw-bold text-dark"><i class="bi bi-1-circle text-primary me-1"></i>
                                            Cara Mengajukan Permintaan</h6>
                                        <ol class="panduan-modal-list text-danger mt-3">
                                            <li class="mb-2">Cari barang di kolom <strong>Barang yang Diminta</strong>,
                                                ketik beberapa huruf agar saran muncul otomatis.</li>
                                            <li class="mb-2">Isi <strong>Jumlah</strong> sesuai kebutuhan. Jika barang
                                                habis, pilih barang lain yang tersedia.</li>
                                            <li class="mb-2">Lengkapi data diri (nama, unit/prodi, dan email aktif)
                                                supaya kami mudah menghubungi Anda.</li>
                                            <li>Klik <strong>Ajukan Permintaan</strong> dan simpan kode resi untuk cek
                                                status.</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 2: Status 1 -->
                            <div class="carousel-item">
                                <div class="row g-4 align-items-start">
                                    <div class="col-lg-6">
                                        <div class="panduan-image-wrap shadow-sm">
                                            <img src="<?= base_url('img/status-permintaan.png') ?>"
                                                alt="Menu Cek Status" class="panduan-image img-fluid">
                                        </div>
                                        <p class="small text-muted mt-2 mb-0 text-center">Letak fitur cek status.</p>
                                    </div>
                                    <div class="col-lg-6">
                                        <h6 class="fw-bold text-dark"><i class="bi bi-2-circle text-primary me-1"></i>
                                            Buka Fitur Cek Status</h6>
                                        <p class="mt-3 text-muted">Untuk melacak sejauh mana permintaan Anda diproses:
                                        </p>
                                        <ol class="panduan-modal-list text-primary">
                                            <li class="mb-2">Scroll ke bagian <strong>Informasi Permintaan</strong> (di
                                                sebelah kanan form).</li>
                                            <li class="mb-2">Temukan panel Cek Status Permintaan.</li>
                                            <li>Klik tombol <strong>Buka Cek Status</strong>.</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 3: Status 2 -->
                            <div class="carousel-item">
                                <div class="row g-4 align-items-start">
                                    <div class="col-lg-6">
                                        <div class="panduan-image-wrap shadow-sm">
                                            <img src="<?= base_url('img/status-permintaan2.png') ?>"
                                                alt="Input Kode Resi" class="panduan-image img-fluid">
                                        </div>
                                        <p class="small text-muted mt-2 mb-0 text-center">Masukkan Kode Resi.</p>
                                    </div>
                                    <div class="col-lg-6">
                                        <h6 class="fw-bold text-dark"><i class="bi bi-3-circle text-primary me-1"></i>
                                            Masukkan Kode Resi</h6>
                                        <p class="mt-3 text-muted">Jendela pencarian status akan muncul:</p>
                                        <ol class="panduan-modal-list text-primary">
                                            <li class="mb-2">Ketik atau <em>paste</em> <strong>Kode Resi</strong> yang
                                                Anda dapatkan saat berhasil mengajukan permintaan.</li>
                                            <li>Klik tombol <strong>Cek Status</strong> untuk memuat data.</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 4: Hasil -->
                            <div class="carousel-item">
                                <div class="row g-4 align-items-start">
                                    <div class="col-lg-6">
                                        <div class="panduan-image-wrap shadow-sm">
                                            <img src="<?= base_url('img/hasil-cek-status.png') ?>"
                                                alt="Hasil Cek Status" class="panduan-image img-fluid">
                                        </div>
                                        <p class="small text-muted mt-2 mb-0 text-center">Tampilan hasil status.</p>
                                    </div>
                                    <div class="col-lg-6">
                                        <h6 class="fw-bold text-dark"><i class="bi bi-4-circle text-primary me-1"></i>
                                            Lihat Hasil Status</h6>
                                        <p class="mt-3 text-muted">Rincian status permintaan akan ditampilkan:</p>
                                        <ol class="panduan-modal-list text-success">
                                            <li class="mb-2">Anda bisa melihat langsung tahapannya
                                                (<strong>Diajukan</strong>, <strong>Disetujui</strong>,
                                                <strong>Didistribusikan</strong>, atau <strong>Dibatalkan</strong>).
                                            </li>
                                            <li>Perhatikan juga <strong>Alasan Admin</strong> jika ada pesan penting
                                                terkait permintaan tersebut.</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev btn-nav-panduan" type="button"
                            data-bs-target="#carouselPanduan" data-bs-slide="prev"
                            style="top: 50%; transform: translateY(-50%); left: 15px; border-radius: 50%; border: 1px solid #ddd; opacity: 1;">
                            <i class="bi bi-chevron-left text-dark fs-3" style="line-height: 1;"></i>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next btn-nav-panduan" type="button"
                            data-bs-target="#carouselPanduan" data-bs-slide="next"
                            style="top: 50%; transform: translateY(-50%); right: 15px; border-radius: 50%; border: 1px solid #ddd; opacity: 1;">
                            <i class="bi bi-chevron-right text-dark fs-3" style="line-height: 1;"></i>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Kode Resi -->
    <div class="modal fade" id="modalKodeResi" tabindex="-1" aria-labelledby="judulModalKodeResi" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content resi-modal-content">
                <div class="modal-header resi-modal-header">
                    <h5 class="modal-title" id="judulModalKodeResi">
                        <i class="bi bi-receipt-cutoff me-2" style="color:var(--primary-dark);"></i>Kode Resi Permintaan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <span class="resi-badge"><i class="bi bi-info-circle"></i> Harap dicatat</span>
                    <p class="mb-3">Permintaan berhasil dikirim. Simpan kode resi berikut untuk pelacakan status.</p>
                    <div class="resi-code-box" id="resiCodeText">-</div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="button" class="btn btn-outline-info w-50" id="btnSalinResi">
                            <i class="bi bi-clipboard me-1"></i>Salin Kode
                        </button>
                        <button type="button" class="btn btn-primary w-50" data-bs-dismiss="modal">
                            <i class="bi bi-check2-circle me-1"></i>Baik
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    $bukaModalCekStatus = (bool) session('_open_track_modal');
    $pesanModalCekStatus = $bukaModalCekStatus ? session('error') : null;
    $bukaModalHasilTrack = (bool) session('_open_track_result_modal');
    $dataHasilTrack = $bukaModalHasilTrack ? (session('track_result_data') ?? []) : [];
    $dataHasilTrack = is_array($dataHasilTrack) ? $dataHasilTrack : [];
    $warnaStatusTrack = (string) ($dataHasilTrack['status_color'] ?? 'secondary');
    if (!in_array($warnaStatusTrack, ['warning', 'info', 'success', 'danger', 'secondary'], true)) {
        $warnaStatusTrack = 'secondary';
    }
    ?>

    <!-- Modal Cek Status -->
    <div class="modal fade" id="modalCekStatus" tabindex="-1" aria-labelledby="judulModalCekStatus" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="judulModalCekStatus">
                        <i class="bi bi-search me-2" style="color:var(--primary-color);"></i>Cek Status Permintaan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Masukkan kode resi untuk melihat status permintaan Anda.</p>

                    <?php if (!empty($pesanModalCekStatus)): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-circle me-1"></i><?= esc((string) $pesanModalCekStatus) ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('track-status') ?>" method="POST" id="formCekStatusBeranda">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_from" value="home-modal">
                        <div class="mb-3">
                            <label class="form-label" for="kodeResiTrackBeranda">Kode Resi</label>
                            <input type="text" class="form-control" id="kodeResiTrackBeranda" name="reference_no"
                                value="<?= esc(old('reference_no', '')) ?>" placeholder="Contoh: 20260414-102530"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" id="btnCekStatusBeranda">
                            <i class="bi bi-search me-1"></i>Cek Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hasil Cek Status -->
    <div class="modal fade" id="modalHasilCekStatus" tabindex="-1" aria-labelledby="judulModalHasilCekStatus"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="judulModalHasilCekStatus">
                        <i class="bi bi-clipboard-data me-2" style="color:var(--primary-color);"></i>Hasil Cek Status
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Kode Resi</span>
                                <strong><?= esc((string) ($dataHasilTrack['reference_no'] ?? '-')) ?></strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Nomor Permintaan</span>
                                <strong><?= esc((string) ($dataHasilTrack['request_no'] ?? '-')) ?></strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Pemohon</span>
                                <strong><?= esc((string) ($dataHasilTrack['borrower_name'] ?? '-')) ?></strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Unit</span>
                                <strong><?= esc((string) ($dataHasilTrack['borrower_unit'] ?? '-')) ?></strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Tanggal Permintaan</span>
                                <strong><?= esc((string) ($dataHasilTrack['request_date'] ?? '-')) ?></strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Status</span>
                                <span class="badge bg-<?= esc($warnaStatusTrack) ?>">
                                    <i
                                        class="bi bi-<?= esc((string) ($dataHasilTrack['status_icon'] ?? 'question-circle')) ?> me-1"></i>
                                    <?= esc((string) ($dataHasilTrack['status_text'] ?? 'Tidak Diketahui')) ?>
                                </span>
                            </div>

                            <?php if (!empty($dataHasilTrack['status_reason'])): ?>
                                <div class="mt-3 p-2 bg-white rounded border small">
                                    <span class="d-block fw-bold text-danger text-uppercase mb-1"
                                        style="font-size: 0.7rem;">Alasan Admin:</span>
                                    <span
                                        class="text-muted italic"><?= nl2br(esc((string) ($dataHasilTrack['status_reason'] ?? ''))) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary w-100 mt-3" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Login -->
    <div class="modal fade" id="modalMasuk" tabindex="-1" aria-labelledby="judulModalMasuk" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="judulModalMasuk">
                        <i class="bi bi-box-seam-fill me-2" style="color:var(--primary-color);"></i>Masuk ke <span
                            class="font-accent">SIMATK</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <?php if (session('loginError')): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i><?= session('loginError') ?>
                        </div>
                    <?php endif ?>

                    <!-- ✅ POST ke /auth/login bukan GET ke /dashboard -->
                    <form action="<?= base_url('auth/login') ?>" method="POST" id="formLogin">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="inputUsername" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="inputUsername" name="username"
                                    value="<?= old('username') ?>" placeholder="Masukkan username" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="inputPassword" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="inputPassword" name="password"
                                    placeholder="Masukkan password" required>
                                <button class="btn btn-outline-secondary" type="button" id="btnTogglePassword"
                                    onclick="togglePassword()">
                                    <i class="bi bi-eye" id="ikonPassword"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="ingatSaya" name="remember">
                                <label class="form-check-label" for="ingatSaya">Ingat saya</label>
                            </div>
                            <a href="#" class="text-decoration-none"
                                style="color:var(--primary-color);font-size:0.9rem;">Lupa password?</a>
                        </div>
                        <button type="submit" class="btn btn-primary btn-login-submit">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                        </button>
                    </form>
                    <hr class="my-4">
                    <div class="text-center">
                        <p class="mb-0 text-muted" style="font-size:0.9rem;">
                            Belum punya akun? <a href="#" class="text-decoration-none"
                                style="color:var(--primary-color);">Hubungi Admin</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll to Top -->
    <button class="scroll-top" id="tombolScrollAtas">
        <i class="bi bi-rocket-takeoff"></i>
    </button>

    <script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/aos/aos.js') ?>"></script>
    <script>
        // -- SIMATK Config for home.js --
        window.SIMATK_HOME = {
            kodeResi: <?= json_encode(session()->getFlashdata('kode_resi') ?: service('request')->getGet('resi')) ?>,
            bukaModalCekStatus: <?= json_encode($bukaModalCekStatus ?? false) ?>,
            bukaModalHasilTrack: <?= json_encode($bukaModalHasilTrack ?? false) ?>,
            openSuccessModal: <?= json_encode((bool)session()->getFlashdata('_open_success_modal')) ?>
        };
    </script>
    <script src="<?= base_url('js/home.js') ?>"></script>
</body>

</html>