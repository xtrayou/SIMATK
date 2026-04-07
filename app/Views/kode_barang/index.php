<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Total: <?= number_format($totalItem, 0, ',', '.') ?> Kode Barang</h5>
                </div>

                <form method="get" action="<?= current_url() ?>" class="row g-2 mb-3" id="searchFormKodeBarang">
                    <div class="col-md-8">
                        <input
                            type="text"
                            class="form-control"
                            name="q"
                            id="q"
                            placeholder="Cari kode atau nama barang..."
                            value="<?= esc($keyword ?? '') ?>"
                            autocomplete="off">
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                    <div class="col-md-2 d-grid">
                        <a href="<?= current_url() ?>" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="kodeBarangTable">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Kode Barang</th>
                                <th width="70%">Nama Peruntukan / Barang</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($daftarKode)): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                        <p class="mt-2 mb-0">Belum ada data kode barang.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($daftarKode as $idx => $kb): ?>
                                    <tr>
                                        <td><?= $idx + 1 ?></td>
                                        <td><span class="badge bg-primary text-white font-monospace"><?= esc($kb['kode']) ?></span></td>
                                        <td class="fw-bold"><?= esc($kb['nama']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- If DataTables is included in the project, we can initialize it here -->
<script>
    $(document).ready(function() {
        // Realtime filter di tabel tanpa reload halaman
        const $search = $('#q');
        const $rows = $('#kodeBarangTable tbody tr');

        $search.on('input', function() {
            const keyword = $(this).val().toLowerCase().trim();

            $rows.each(function() {
                const rowText = $(this).text().toLowerCase();
                const isEmptyState = $(this).find('td[colspan="3"]').length > 0;

                if (isEmptyState) {
                    return;
                }

                $(this).toggle(rowText.includes(keyword));
            });
        });
    });
</script>
<?= $this->endSection() ?>