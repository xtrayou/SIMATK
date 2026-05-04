<?= $this->extend('layouts/app') ?>

<?= $this->section('content'); ?>

<?php
$reportTitle = 'Stok Saat Ini';
$reportDescription = 'Menampilkan jumlah stok barang saat ini.';
$reportIconClass = 'bi-box-seam';
?>

<!-- Report Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-1">
                            <i class="bi <?= esc($reportIconClass) ?> text-primary"></i>
                            <?= esc($reportTitle) ?>
                        </h4>
                        <p class="text-muted mb-0">
                            <?= esc($reportDescription) ?>
                            Periode laporan:
                            <strong><?= $filters['month'] ?? date('m') ?>/<?= $filters['year'] ?? date('Y') ?></strong>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="btn-group">
                            <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-download me-2"></i>Export
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" onclick="exportReport('excel'); return false;">
                                    <i class="bi bi-file-earmark-excel me-2"></i>Excel
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="exportReport('pdf'); return false;">
                                    <i class="bi bi-file-earmark-pdf me-2"></i>PDF
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="printReport(); return false;">
                                    <i class="bi bi-printer me-2"></i>Print
                                </a></li>
                            </ul>
                        </div>
                        <small class="d-block text-muted mt-1"><i class="bi bi-info-circle me-1"></i>Export hanya memuat barang dengan stok &gt; 0</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Summary Statistics -->
<div class="row mb-4">
    <div class="col-lg-2 col-md-4 col-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <div class="stats-icon blue mb-3">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h4 class="text-primary"><?= number_format($summary['total_products']) ?></h4>
                <p class="text-muted mb-0">Total Barang</p>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <div class="stats-icon green mb-3">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <h4 class="text-success"><?= format_currency($summary['total_value']) ?></h4>
                <p class="text-muted mb-0">Nilai Total</p>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <div class="stats-icon purple mb-3">
                    <i class="bi bi-boxes"></i>
                </div>
                <h4 class="text-info"><?= number_format($summary['total_quantity']) ?></h4>
                <p class="text-muted mb-0">Total Quantity</p>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6 mb-3">
        <div class="card text-center border-success">
            <div class="card-body">
                <div class="stats-icon green mb-3">
                    <i class="bi bi-check-circle"></i>
                </div>
                <h4 class="text-success"><?= number_format($summary['normal_stock']) ?></h4>
                <p class="text-muted mb-0">Stok Normal</p>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6 mb-3">
        <div class="card text-center border-warning">
            <div class="card-body">
                <div class="stats-icon orange mb-3">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h4 class="text-warning"><?= number_format($summary['low_stock']) ?></h4>
                <p class="text-muted mb-0">Stok Rendah</p>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6 mb-3">
        <div class="card text-center border-danger">
            <div class="card-body">
                <div class="stats-icon red mb-3">
                    <i class="bi bi-x-circle"></i>
                </div>
                <h4 class="text-danger"><?= number_format($summary['out_of_stock']) ?></h4>
                <p class="text-muted mb-0">Stok Habis</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div>
            <button class="btn btn-outline-secondary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="bi bi-funnel me-2"></i>Filter & Sorting
            </button>

            <!-- Filter Form -->
            <div class="collapse" id="filterCollapse">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" id="filterForm" class="row g-3">
                            <div class="col-md-2">
                                <label for="month" class="form-label">Bulan</label>
                                <select class="form-select" id="month" name="month">
                                    <?php for ($m = 1; $m <= 12; $m++): ?>
                                        <option value="<?= sprintf('%02d', $m) ?>" <?= ($filters['month'] ?? date('m')) == sprintf('%02d', $m) ? 'selected' : '' ?>>
                                            <?= $m ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="year" class="form-label">Tahun</label>
                                <input type="number" class="form-control" id="year" name="year" value="<?= $filters['year'] ?? date('Y') ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="category" class="form-label">Kategori</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">Semua Kategori</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>"
                                            <?= $filters['category'] == $category['id'] ? 'selected' : '' ?>>
                                            <?= esc($category['name']) ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="stock_status" class="form-label">Status Stok</label>
                                <select class="form-select" id="stock_status" name="stock_status">
                                    <option value="">Semua Status</option>
                                    <option value="normal" <?= $filters['stock_status'] == 'normal' ? 'selected' : '' ?>>
                                        Normal
                                    </option>
                                    <option value="low_stock" <?= $filters['stock_status'] == 'low_stock' ? 'selected' : '' ?>>
                                        Stok Rendah
                                    </option>
                                    <option value="out_of_stock" <?= $filters['stock_status'] == 'out_of_stock' ? 'selected' : '' ?>>
                                        Stok Habis
                                    </option>
                                    <option value="overstocked" <?= $filters['stock_status'] == 'overstocked' ? 'selected' : '' ?>>
                                        Overstocked
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="sort_by" class="form-label">Urutkan</label>
                                <select class="form-select" id="sort_by" name="sort_by">
                                    <option value="name" <?= $filters['sort_by'] == 'name' ? 'selected' : '' ?>>Nama</option>
                                    <option value="current_stock" <?= $filters['sort_by'] == 'current_stock' ? 'selected' : '' ?>>Stok</option>
                                    <option value="stock_value" <?= $filters['sort_by'] == 'stock_value' ? 'selected' : '' ?>>Nilai</option>
                                    <option value="category_name" <?= $filters['sort_by'] == 'category_name' ? 'selected' : '' ?>>Kategori</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="sort_order" class="form-label">Arah</label>
                                <select class="form-select" id="sort_order" name="sort_order">
                                    <option value="ASC" <?= $filters['sort_order'] == 'ASC' ? 'selected' : '' ?>>A-Z / Kecil-Besar</option>
                                    <option value="DESC" <?= $filters['sort_order'] == 'DESC' ? 'selected' : '' ?>>Z-A / Besar-Kecil</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search me-2"></i> Filter
                                </button>
                                <a href="<?= base_url('/reports/stock') ?>" class="btn btn-outline-secondary">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (($filters['is_archived'] ?? false) && !($filters['archive_found'] ?? true)): ?>
    <?php // Beri peringatan jika mode arsip aktif tetapi data periode tersebut tidak ditemukan. 
    ?>
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-warning mb-0">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Data arsip laporan stok untuk periode <strong><?= esc(($filters['month'] ?? date('m')) . '/' . ($filters['year'] ?? date('Y'))) ?></strong> tidak ditemukan.
                Laporan periode ini tidak bisa dianggap valid sampai arsip bulan tersebut tersedia.
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if (!empty($category_breakdown)): ?>
    <?php // Tampilkan visualisasi komposisi nilai stok per kategori saat mode laporan stok aktif. 
    ?>
    <!-- Category Breakdown Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-pie-chart"></i> Breakdown per Kategori</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div style="position:relative; height:300px;">
                                <canvas id="categoryChart"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Kategori</th>
                                            <th>Barang</th>
                                            <th>Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($category_breakdown as $catName => $catData): ?>
                                            <tr>
                                                <td><strong><?= esc($catName) ?></strong></td>
                                                <td><?= number_format($catData['products']) ?></td>
                                                <td><?= format_currency($catData['total_value']) ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

    <!-- Detailed Stock Report -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Detail Laporan Stok</h5>
                    <small class="text-muted">Total: <?= number_format(count($products)) ?> barang</small>
                </div>
                <div class="card-body">
                    <?php if (!empty($products)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover datatable" id="stockReportTable">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="25%">Barang</th>
                                        <th width="15%">Kategori</th>
                                        <th width="10%">Kode Barang</th>
                                        <th width="10%">Stok Saat Ini</th>
                                        <th width="10%">Min. Stok</th>
                                        <th width="10%">Status</th>
                                        <th width="15%">Nilai Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $index => $barang): ?>
                                        <tr class="<?= $barang['stock_status'] == 'out_of_stock' ? 'table-danger' : ($barang['stock_status'] == 'low_stock' ? 'table-warning' : '') ?>">
                                            <td><?= $index + 1 ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                        <div class="avatar-content bg-<?= $barang['stock_status'] == 'out_of_stock' ? 'danger' : ($barang['stock_status'] == 'low_stock' ? 'warning' : 'success') ?> text-white">
                                                            <i class="bi bi-box"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?= esc($barang['name']) ?></h6>
                                                        <small class="text-muted"><?= $barang['unit'] ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?= esc($barang['category_name']) ?></span>
                                            </td>
                                            <td>
                                                <code><?= $barang['sku'] ?></code>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <strong class="<?= $barang['stock_status'] == 'out_of_stock' ? 'text-danger' : ($barang['stock_status'] == 'low_stock' ? 'text-warning' : 'text-success') ?> fs-6">
                                                        <?= number_format($barang['current_stock']) ?>
                                                    </strong>
                                                    <?php if (($barang['stock_baik'] ?? 0) > 0 || ($barang['stock_rusak'] ?? 0) > 0): ?>
                                                        <div class="mt-1" style="font-size: 0.75rem;">
                                                            <span class="text-success" title="Barang Kondisi Baik"><i class="bi bi-check-circle"></i> Baik: <?= number_format($barang['stock_baik'] ?? $barang['current_stock']) ?></span><br>
                                                            <?php if (($barang['stock_rusak'] ?? 0) > 0): ?>
                                                                <span class="text-danger" title="Barang Kondisi Rusak"><i class="bi bi-x-circle"></i> Rusak: <?= number_format($barang['stock_rusak']) ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted"><?= number_format($barang['min_stock']) ?></span>
                                            </td>
                                            <td>
                                                <?= format_stock_badge($barang['current_stock'], $barang['min_stock']) ?>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?= format_currency($barang['stock_value']) ?></strong>
                                                    <?php if ($barang['price'] > 0): ?>
                                                        <small class="d-block text-muted">
                                                            @ <?= format_currency($barang['price']) ?>
                                                        </small>
                                                    <?php endif ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-active">
                                        <th colspan="4">TOTAL</th>
                                        <th><?= number_format($summary['total_quantity']) ?></th>
                                        <th>-</th>
                                        <th>-</th>
                                        <th><?= format_currency($summary['total_value']) ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">Tidak ada data</h5>
                            <p class="text-muted">Ubah filter untuk melihat data yang berbeda</p>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>



<?= $this->endSection(); ?>
<?= $this->section('breadcrumb'); ?>
<ol class="breadcumb">
    <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?= base_url('/reports/stock'); ?>">Reports</a></li>
    <li class="breadcrumb-item active" aria-current="page">>Stock Report</a></li>
</ol>
<?= $this->endSection() ?>

<?= $this->section('scripts'); ?>
<?php
// Data PHP yang dibutuhkan JS diinjeksi via window.SIMATK_STOCK
$chartLabels = json_encode(array_keys($category_breakdown ?? []));
$chartData   = json_encode(array_column($category_breakdown ?? [], 'total_value'));
$hasChart    = !empty($category_breakdown);
?>
<script>
    // Injeksi data PHP ke namespace global agar reports-stock.js bisa membacanya
    window.SIMATK_STOCK = {
        exportBaseUrl: '<?= base_url('/reports/stock/export/') ?>',
        currentMonth:  '<?= date('m') ?>',
        currentYear:   '<?= date('Y') ?>',
        hasChart:      <?= $hasChart ? 'true' : 'false' ?>,
        chartLabels:   <?= $chartLabels ?>,
        chartData:     <?= $chartData ?>
    };
</script>

<!-- JS murni dipisah ke file eksternal -->
<script src="<?= base_url('js/reports-stock.js') ?>"></script>
<?= $this->endSection(); ?>

<?= $this->section('styles') ?>
<!-- CSS dipisah ke file eksternal -->
<link rel="stylesheet" href="<?= base_url('css/reports-stock.css') ?>">
<?= $this->endSection() ?>