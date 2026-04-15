<section class="stats-section">
    <div class="container">
        <div class="row" data-aos="fade-up">
            <div class="col-md-4 col-6">
                <div class="stat-item"><span class="stat-number" data-count="<?= $stats['total_barang'] ?? 0 ?>">0</span><span class="stat-label">Jenis Barang</span></div>
            </div>
            <div class="col-md-4 col-6">
                <div class="stat-item"><span class="stat-number" data-count="<?= $stats['total_kategori'] ?? 0 ?>">0</span><span class="stat-label">Kategori</span></div>
            </div>
            <div class="col-md-4 col-6">
                <div class="stat-item"><span class="stat-number" data-count="<?= $stats['jam_operasi'] ?? 8 ?>">0</span><span class="stat-label">Jam Operasional</span></div>
            </div>
        </div>
    </div>
</section>