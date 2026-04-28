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
                <p>Hubungi Admin di <a href="https://wa.me/6287896314494" target="_blank" class="text-decoration-none fw-bold" style="color:var(--primary-color);">+62 878-9631-4494</a></p>
                <p class="mt-1 small text-muted">Akses layanan student services di <a href="https://unsika.link/layananfasilkom" target="_blank">Layanan Fasilkom</a></p>
            </div>
        </div>
        
        <hr style="opacity:0.2;">

        <!-- Tracking Banner -->
        <div class="track-request-banner mb-4 p-3 rounded" style="background: rgba(0, 163, 255, 0.1); border: 1px solid rgba(0, 163, 255, 0.2);">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1 fw-bold" style="color:var(--primary-color);"><i class="bi bi-search me-2"></i>Lacak Permintaan</h6>
                    <p class="mb-0 small text-light">Cek status pengajuan Anda dengan kode resi</p>
                </div>
                <button type="button" class="btn btn-primary btn-sm px-3 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCekStatus">
                    Lacak
                </button>
            </div>
        </div>

        <h6 class="mb-3"><i class="bi bi-tags me-2"></i>Kategori Tersedia:</h6>

        <div class="kategori-preview">
            <?php foreach ($kategoriPreview as $kategori): ?>
                <span class="barang-badge" title="<?= esc($kategori['name']) ?>"><?= esc($kategori['name']) ?></span>
            <?php endforeach ?>
        </div>

        <?php if (!empty($kategoriLainnya)): ?>
            <button class="btn btn-kategori-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#kategoriLainnyaCollapse" aria-expanded="false" aria-controls="kategoriLainnyaCollapse">
                Lihat <?= count($kategoriLainnya) ?> kategori lainnya
            </button>
            <div class="collapse" id="kategoriLainnyaCollapse">
                <div class="kategori-all-wrap">
                    <div class="kategori-all">
                        <?php foreach ($kategoriLainnya as $kategori): ?>
                            <span class="barang-badge" title="<?= esc($kategori['name']) ?>"><?= esc($kategori['name']) ?></span>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        <?php endif ?>
    </div>
</div>