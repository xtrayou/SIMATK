<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php $userRole = session()->get('role'); ?>

<!-- Kartu Statistik Utama -->
<div class="row mb-4">
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card inventory-card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon purple mb-2">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Total Barang</h6>
                        <h6 class="font-extrabold mb-0" id="totalBarang"><?= number_format($total_products) ?></h6>
                        <small class="text-muted">Barang aktif</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3 col-md-6">
        <div class="card inventory-card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon blue mb-2">
                            <i class="bi bi-collection-fill"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Kategori</h6>
                        <h6 class="font-extrabold mb-0"><?= number_format($total_categories) ?></h6>
                        <small class="text-muted">Kategori aktif</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($userRole === 'superadmin'): ?>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card inventory-card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon green mb-2">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Nilai Inventaris</h6>
                            <h6 class="font-extrabold mb-0"><?= format_currency($inventory_value) ?></h6>
                            <small class="text-muted">Total valuasi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="col-6 col-lg-3 col-md-6">
        <div class="card inventory-card <?= $low_stock_count > 0 ? 'low-stock alert-stock' : '' ?>">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon <?= $low_stock_count > 0 ? 'red' : 'green' ?> mb-2">
                            <i
                                class="bi bi-<?= $low_stock_count > 0 ? 'exclamation-triangle-fill' : 'check-circle-fill' ?>"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Stok Rendah</h6>
                        <h6 class="font-extrabold mb-0 <?= $low_stock_count > 0 ? 'text-danger' : 'text-success' ?>">
                            <?= number_format($low_stock_count) ?>
                        </h6>
                        <small class="text-muted">Perlu perhatian</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Cepat -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-speedometer2"></i> Statistik Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 col-md-3">
                        <div class="quick-stat">
                            <h4 class="text-info"><?= number_format($quick_stats['today_movements']) ?></h4>
                            <p class="text-muted mb-0">Transaksi Hari Ini</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="quick-stat">
                            <h4 class="text-success"><?= number_format($quick_stats['this_week_in']) ?></h4>
                            <p class="text-muted mb-0">Barang Masuk (7 hari)</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="quick-stat">
                            <h4 class="text-danger"><?= number_format($quick_stats['this_week_out']) ?></h4>
                            <p class="text-muted mb-0">Barang Keluar (7 hari)</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="quick-stat">
                            <h4 class="text-warning"><?= number_format($out_of_stock_count) ?></h4>
                            <p class="text-muted mb-0">Stok Habis</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Aksi Cepat -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-lightning-fill"></i> Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if ($userRole === 'admin'): ?>
                        <div class="col-md-3 mb-2">
                            <a href="<?= base_url('/stock/in') ?>" class="btn btn-info w-100 btn-lg">
                                <i class="bi bi-arrow-down-circle"></i>
                                <div class="d-block">
                                    <strong>Barang Masuk</strong>
                                    <small class="d-block">Input stok baru</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?= base_url('/stock/out') ?>" class="btn btn-warning w-100 btn-lg">
                                <i class="bi bi-arrow-up-circle"></i>
                                <div class="d-block">
                                    <strong>Barang Keluar</strong>
                                    <small class="d-block">Keluarkan stok</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?= base_url('/requests') ?>" class="btn btn-primary w-100 btn-lg">
                                <i class="bi bi-clipboard-check"></i>
                                <div class="d-block">
                                    <strong>Permintaan</strong>
                                    <small class="d-block">Kelola permintaan</small>
                                </div>
                            </a>
                        </div>
                    <?php elseif ($userRole === 'superadmin'): ?>
                        <div class="col-md-3 mb-2">
                            <a href="<?= base_url('/users') ?>" class="btn btn-primary w-100 btn-lg text-white">
                                <i class="bi bi-people"></i>
                                <div class="d-block">
                                    <strong>Pengguna</strong>
                                    <small class="d-block text-white-50">Kelola hak akses</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?= base_url('/reports/movements') ?>" class="btn btn-info w-100 btn-lg text-white">
                                <i class="bi bi-arrow-repeat"></i>
                                <div class="d-block">
                                    <strong>Pergerakan</strong>
                                    <small class="d-block text-white-50">Laporan mutasi</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?= base_url('/reports/stock') ?>" class="btn btn-success w-100 btn-lg text-white">
                                <i class="bi bi-box-seam"></i>
                                <div class="d-block">
                                    <strong>Stok</strong>
                                    <small class="d-block text-white-50">Laporan stok</small>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Konten Utama Dashboard -->
<div class="row">
    <!-- Grafik Pergerakan Stok -->
    <div class="col-12 col-xl-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="bi bi-graph-up"></i> Pergerakan Stok 6 Bulan Terakhir</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                        data-bs-toggle="dropdown">
                        <i class="bi bi-gear"></i> Opsi
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="#" onclick="eksporGrafik('pergerakan')">
                                <i class="bi bi-download"></i> Ekspor Grafik
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= base_url('/reports/movements') ?>">
                                <i class="bi bi-file-earmark-text"></i> Laporan Detail
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <canvas id="grafikPergerakanStok" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Grafik Pie Status Stok -->
    <div class="col-12 col-xl-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-pie-chart"></i> Status Stok</h5>
            </div>
            <div class="card-body">
                <div style="position:relative; max-height:280px;">
                    <canvas id="grafikStatusStok"></canvas>
                </div></canvas>
                <div class="mt-3">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="stock-legend">
                                <div class="legend-color bg-success"></div>
                                <small>Normal</small>
                                <strong class="d-block"><?= $chart_data['stock_status_pie']['data'][2] ?></strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stock-legend">
                                <div class="legend-color bg-warning"></div>
                                <small>Rendah</small>
                                <strong
                                    class="d-block text-warning"><?= $chart_data['stock_status_pie']['data'][1] ?></strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stock-legend">
                                <div class="legend-color bg-danger"></div>
                                <small>Habis</small>
                                <strong
                                    class="d-block text-danger"><?= $chart_data['stock_status_pie']['data'][0] ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Aktivitas Terbaru -->
    <div class="col-12 col-xl-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="bi bi-clock-history"></i> Aktivitas Terbaru</h5>
                <a href="<?= base_url('/stock/history') ?>" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($recent_movements)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="25%">Waktu</th>
                                    <th width="35%">Barang</th>
                                    <th width="20%">Tipe</th>
                                    <th width="20%">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_movements as $mutasi): ?>
                                    <tr>
                                        <td>
                                            <small class="text-muted">
                                                <?= time_ago($mutasi['created_at']) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?= esc($mutasi['product_name']) ?></strong>
                                                <small class="d-block text-muted"><?= esc($mutasi['product_sku']) ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <?= format_movement_badge($mutasi['type']) ?>
                                        </td>
                                        <td>
                                            <strong class="<?= $mutasi['type'] === 'IN' ? 'text-success' : 'text-danger' ?>">
                                                <?= $mutasi['type'] === 'IN' ? '+' : '-' ?>        <?= number_format($mutasi['quantity']) ?>
                                            </strong>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted">Belum ada aktivitas</p>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <!-- Barang Stok Rendah -->
    <div class="col-12 col-xl-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="bi bi-exclamation-triangle-fill text-warning"></i> Barang Stok Rendah</h5>
                <a href="<?= base_url('/stock/alerts') ?>" class="btn btn-sm btn-outline-warning">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($low_stock_products)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="40%">Barang</th>
                                    <th width="20%">Stok</th>
                                    <th width="20%">Min</th>
                                    <th width="20%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($low_stock_products as $barang): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-3">
                                                    <div class="avatar-content bg-warning text-white">
                                                        <i class="bi bi-box"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <strong><?= esc($barang['name']) ?></strong>
                                                    <small
                                                        class="d-block text-muted"><?= esc($barang['category_name']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong
                                                class="<?= $barang['current_stock'] == 0 ? 'text-danger' : 'text-warning' ?>">
                                                <?= number_format($barang['current_stock']) ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <span class="text-muted"><?= number_format($barang['min_stock']) ?></span>
                                        </td>
                                        <td>
                                            <?= format_stock_badge($barang['current_stock'], $barang['min_stock']) ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <div class="alert alert-warning">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong><?= count($low_stock_products) ?></strong> barang membutuhkan restok segera.
                            <a href="<?= base_url('/stock/in') ?>" class="alert-link">Tambah stok sekarang</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle-fill fs-1 text-success"></i>
                        <h6 class="text-success mt-2">Semua Stok Normal</h6>
                        <p class="text-muted">Tidak ada barang dengan stok rendah</p>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<?php if ($userRole === 'superadmin'): ?>
    <!-- Top Barang Berdasarkan Nilai -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="bi bi-trophy-fill text-warning"></i> Top Barang Berdasarkan Nilai</h5>
                    <a href="<?= base_url('/reports/valuation') ?>" class="btn btn-sm btn-outline-primary">
                        Laporan Detail
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($top_products)): ?>
                        <div class="row">
                            <?php foreach ($top_products as $peringkat => $barangTeratas): ?>
                                <div class="col-12 col-md-6 col-lg-4 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="rank-badge me-3">
                                                    <span
                                                        class="badge bg-<?= $peringkat == 0 ? 'warning' : ($peringkat == 1 ? 'secondary' : 'dark') ?> fs-6">
                                                        #<?= $peringkat + 1 ?>
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1"><?= esc($barangTeratas['name']) ?></h6>
                                                    <small class="text-muted"><?= esc($barangTeratas['category_name']) ?></small>
                                                    <div class="mt-2">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <small class="text-muted">Stok:</small>
                                                                <strong
                                                                    class="d-block"><?= number_format($barangTeratas['current_stock']) ?></strong>
                                                            </div>
                                                            <div class="col-6">
                                                                <small class="text-muted">Nilai:</small>
                                                                <strong class="d-block text-success">
                                                                    <?= format_currency($barangTeratas['total_value']) ?>
                                                                </strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-graph-down fs-1 text-muted"></i>
                            <p class="text-muted">Belum ada data barang dengan nilai tinggi</p>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php
$chartLabels   = json_encode($chart_data['monthly_movements']['labels']);
$chartIn       = json_encode($chart_data['monthly_movements']['stock_in']);
$chartOut      = json_encode($chart_data['monthly_movements']['stock_out']);
$pieLabels     = json_encode($chart_data['stock_status_pie']['labels']);
$pieData       = json_encode($chart_data['stock_status_pie']['data']);
$pieColors     = json_encode($chart_data['stock_status_pie']['colors']);
$lowStockCount = (int) $low_stock_count;
$apiStatsUrl   = base_url('/api/dashboard/stats');
$faviconUrl    = base_url('assets/static/images/logo/favicon.png');
?>
<script>
    window.DASHBOARD_CFG = {
        chartLabels: <?= $chartLabels ?>,
        chartIn: <?= $chartIn ?>,
        chartOut: <?= $chartOut ?>,
        pieLabels: <?= $pieLabels ?>,
        pieData: <?= $pieData ?>,
        pieColors: <?= $pieColors ?>,
        lowStockCount: <?= $lowStockCount ?>,
        apiStatsUrl: '<?= $apiStatsUrl ?>',
        faviconUrl: '<?= $faviconUrl ?>'
    };
</script>
<script src="<?= base_url('js/dashboard.js') ?>"></script>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
<?= $this->endSection() ?>