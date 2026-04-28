<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$abcSummary = $analytics['abc_analysis']['summary'] ?? [];
$totalAbcProducts = max(1, (int) ($abcSummary['total_products'] ?? 0));
$aCount = (int) ($abcSummary['A_count'] ?? 0);
$bCount = (int) ($abcSummary['B_count'] ?? 0);
$cCount = (int) ($abcSummary['C_count'] ?? 0);

$reorderSuggestions = $analytics['reorder_suggestions'] ?? [];
$topReorderSuggestions = array_slice($reorderSuggestions, 0, 10);
$urgencyColors = [
    'critical' => 'danger',
    'high' => 'warning',
    'medium' => 'info',
    'low' => 'secondary'
];
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-1">
                            <i class="bi bi-graph-up-arrow"></i>
                            Analytics Dashboard
                        </h4>
                        <p class="mb-0 opacity-75">
                            Advanced analytics dan business insights
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="btn-group">
                            <select class="form-select form-select-sm bg-light" id="periodSelector">
                                <option value="7" <?= $period == '7' ? 'selected' : '' ?>>7 Hari</option>
                                <option value="30" <?= $period == '30' ? 'selected' : '' ?>>30 Hari</option>
                                <option value="90" <?= $period == '90' ? 'selected' : '' ?>>90 Hari</option>
                                <option value="365" <?= $period == '365' ? 'selected' : '' ?>>1 Tahun</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Key Performance Indicators -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="kpi-icon bg-primary text-white mb-3">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <h3 class="text-primary"><?= $analytics['inventory_turnover']['turnover_rate'] ?></h3>
                <h6 class="text-muted">Inventory Turnover</h6>
                <small class="text-muted">
                    <?= number_format($analytics['inventory_turnover']['total_sold']) ?> items sold
                </small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="kpi-icon bg-success text-white mb-3">
                    <i class="bi bi-check-circle"></i>
                </div>
                <h3 class="text-success"><?= $analytics['performance_metrics']['order_fulfillment_rate'] ?>%</h3>
                <h6 class="text-muted">Order Fulfillment</h6>
                <small class="text-muted">Orders delivered on time</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="kpi-icon bg-info text-white mb-3">
                    <i class="bi bi-bullseye"></i>
                </div>
                <h3 class="text-info"><?= $analytics['performance_metrics']['stock_accuracy'] ?>%</h3>
                <h6 class="text-muted">Stock Accuracy</h6>
                <small class="text-muted">System vs physical count</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="kpi-icon bg-warning text-white mb-3">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h3 class="text-warning"><?= $analytics['performance_metrics']['stockout_frequency'] ?></h3>
                <h6 class="text-muted">Stockout Incidents</h6>
                <small class="text-muted">Last <?= $period ?> days</small>
            </div>
        </div>
    </div>
</div>

<!-- ABC Analysis -->
<div class="row mb-4">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-pie-chart-fill"></i> ABC Analysis</h5>
                <small class="text-muted">Classification based on stock value</small>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <canvas id="abcChart" height="200"></canvas>
                    </div>
                    <div class="col-md-6">
                        <div class="abc-summary">
                            <div class="abc-category mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="abc-indicator bg-danger me-2"></div>
                                    <h6 class="mb-0">Category A - High Value</h6>
                                </div>
                                <p class="mb-1"><?= $aCount ?> products (<?= number_format(($aCount / $totalAbcProducts) * 100, 1) ?>%)</p>
                                <small class="text-muted">Requires tight control and frequent review</small>
                            </div>

                            <div class="abc-category mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="abc-indicator bg-warning me-2"></div>
                                    <h6 class="mb-0">Category B - Medium Value</h6>
                                </div>
                                <p class="mb-1"><?= $bCount ?> products (<?= number_format(($bCount / $totalAbcProducts) * 100, 1) ?>%)</p>
                                <small class="text-muted">Moderate control with periodic review</small>
                            </div>

                            <div class="abc-category">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="abc-indicator bg-success me-2"></div>
                                    <h6 class="mb-0">Category C - Low Value</h6>
                                </div>
                                <p class="mb-1"><?= $cCount ?> products (<?= number_format(($cCount / $totalAbcProducts) * 100, 1) ?>%)</p>
                                <small class="text-muted">Simple controls and bulk management</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h5><i class="bi bi-speedometer2"></i> Performance Score</h5>
            </div>
            <div class="card-body d-flex flex-column justify-content-center">
                <div class="text-center">
                    <div class="performance-circle mb-3">
                        <canvas id="performanceGauge" width="150" height="150"></canvas>
                        <div class="performance-score">
                            <h2>87</h2>
                            <small>Score</small>
                        </div>
                    </div>
                    <h5 class="text-success">Excellent Performance</h5>
                    <p class="text-muted">Your inventory management is performing well above industry standards.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reorder Suggestions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5><i class="bi bi-arrow-clockwise"></i> Reorder Suggestions</h5>
                    <small class="text-muted">Products that need attention</small>
                </div>
                <span class="badge bg-warning fs-6"><?= count($reorderSuggestions) ?> items</span>
            </div>
            <div class="card-body">
                <?php if (!empty($reorderSuggestions)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Current Stock</th>
                                    <th>Urgency</th>
                                    <th>Days Until Stockout</th>
                                    <th>Suggested Order</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topReorderSuggestions as $suggestion): ?>
                                    <tr class="<?= $suggestion['urgency'] == 'critical' ? 'table-danger' : ($suggestion['urgency'] == 'high' ? 'table-warning' : '') ?>">
                                        <td>
                                            <div>
                                                <h6 class="mb-0"><?= esc($suggestion['product']['name']) ?></h6>
                                                <small class="text-muted"><?= $suggestion['product']['category_name'] ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <strong class="<?= $suggestion['urgency'] == 'critical' ? 'text-danger' : 'text-warning' ?>">
                                                <?= number_format($suggestion['product']['current_stock']) ?>
                                            </strong>
                                            <small class="d-block text-muted">Min: <?= number_format($suggestion['product']['min_stock']) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $urgencyColors[$suggestion['urgency']] ?? 'secondary' ?>">
                                                <?= ucfirst($suggestion['urgency']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong><?= $suggestion['days_until_stockout'] ?></strong> days
                                        </td>
                                        <td>
                                            <strong class="text-success"><?= number_format($suggestion['suggested_order_quantity']) ?></strong>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('/stock/in?product=' . $suggestion['product']['id']) ?>"
                                                class="btn btn-success btn-sm">
                                                <i class="bi bi-plus-circle"></i> Restock
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if (count($reorderSuggestions) > 10): ?>
                        <div class="text-center mt-3">
                            <a href="<?= base_url('/stock/alerts') ?>" class="btn btn-outline-primary">
                                View All <?= count($reorderSuggestions) ?> Suggestions
                            </a>
                        </div>
                    <?php endif ?>

                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle-fill fs-1 text-success"></i>
                        <h6 class="text-success mt-2">All Products Well Stocked</h6>
                        <p class="text-muted">No immediate reorder required</p>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<!-- Trend Analysis -->
<div class="row">
    <div class="col-12 col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-graph-up"></i> Stock Movement Trends</h5>
            </div>
            <div class="card-body">
                <canvas id="movementTrendChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-bar-chart"></i> Inventory Value Trend</h5>
            </div>
            <div class="card-body">
                <canvas id="valueTrendChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('breadcrumb'); ?>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?= base_url('/reports/stock') ?>">Reports</a></li>
    <li class="breadcrumb-item active" aria-current="page">Analytics</a></li>
</ol>
<?= $this->endSection() ?>

<?= $this->section('scripts'); ?>
<script>
    window.ANALYTICS_CFG = {
        baseUrl: '<?= base_url('/reports/analytics') ?>',
        period: <?= $period ?>,
        aCount: <?= $aCount ?>,
        bCount: <?= $bCount ?>,
        cCount: <?= $cCount ?>
    };
</script>
<script src="<?= base_url('js/analytics.js') ?>"></script>
<?= $this->endSection(); ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/analytics.css') ?>">
<?= $this->endSection(); ?>