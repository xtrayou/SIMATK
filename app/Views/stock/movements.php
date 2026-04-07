<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title & Breadcrumbs -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-sm-0 font-size-18"><?= $page_title ?></h4>
                        <p class="text-muted mb-0"><?= $page_subtitle ?></p>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active"><?= $page_title ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card card-h-100 bg-<?= $current_type === 'IN' ? 'success' : 'info' ?> text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-white-70 mb-2 d-block">Total Transaksi</span>
                                <span class="fw-bold fs-3"><?= number_format($stats['total_transactions']) ?></span>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="fas fa-<?= $current_type === 'IN' ? 'arrow-down' : 'arrow-up' ?> fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-h-100 bg-<?= $current_type === 'IN' ? 'success' : 'warning' ?> text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-white-70 mb-2 d-block">Total Quantity</span>
                                <span class="fw-bold fs-3"><?= number_format($stats['total_quantity']) ?></span>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="fas fa-boxes fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card card-h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="text-muted fw-normal mt-0">Status Operasi</h5>
                                <h3 class="mb-3 text-<?= $current_type === 'IN' ? 'success' : 'info' ?>">
                                    <i class="fas fa-<?= $current_type === 'IN' ? 'download' : 'upload' ?> me-2"></i>
                                    <?= $stats['type_label'] ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <!-- Tab Navigation -->
                        <div class="d-flex justify-content-between align-items-center">
                            <ul class="nav nav-pills" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link <?= $current_type === 'IN' ? 'active' : '' ?>"
                                        href="<?= current_url() ?>?type=IN<?= $filters['product'] ? '&product=' . $filters['product'] : '' ?><?= $filters['category'] ? '&category=' . $filters['category'] : '' ?>">
                                        <i class="fas fa-download me-2"></i>Barang Masuk
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= $current_type === 'OUT' ? 'active' : '' ?>"
                                        href="<?= current_url() ?>?type=OUT<?= $filters['product'] ? '&product=' . $filters['product'] : '' ?><?= $filters['category'] ? '&category=' . $filters['category'] : '' ?>">
                                        <i class="fas fa-upload me-2"></i>Barang Keluar
                                    </a>
                                </li>
                            </ul>

                            <div>
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                                    <i class="fas fa-filter me-2"></i>Filter
                                </button>
                                <button class="btn btn-<?= $current_type === 'IN' ? 'success' : 'warning' ?>" onclick="showAddModal()">
                                    <i class="fas fa-plus me-2"></i>Tambah <?= $current_type === 'IN' ? 'Barang Masuk' : 'Barang Keluar' ?>
                                </button>
                            </div>
                        </div>

                        <!-- Filter Form (Collapsible) -->
                        <div class="collapse mt-3" id="filterCollapse">
                            <div class="row g-3">
                                <?= form_open(current_url(), ['method' => 'get', 'class' => 'row g-3']) ?>
                                <input type="hidden" name="type" value="<?= $current_type ?>">

                                <div class="col-md-3">
                                    <label class="form-label">Produk</label>
                                    <select class="form-select" name="product">
                                        <option value="">Semua Produk</option>
                                        <?php foreach ($products as $product): ?>
                                            <option value="<?= $product['id'] ?>"
                                                <?= $filters['product'] == $product['id'] ? 'selected' : '' ?>>
                                                <?= esc($product['name']) ?> (<?= esc($product['sku']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Kategori</label>
                                    <select class="form-select" name="category">
                                        <option value="">Semua Kategori</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>"
                                                <?= $filters['category'] == $category['id'] ? 'selected' : '' ?>>
                                                <?= esc($category['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <input type="date" class="form-control" name="start_date"
                                        value="<?= $filters['start_date'] ?>">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Tanggal Akhir</label>
                                    <input type="date" class="form-control" name="end_date"
                                        value="<?= $filters['end_date'] ?>">
                                </div>

                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="<?= current_url() ?>?type=<?= $current_type ?>" class="btn btn-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                                <?= form_close() ?>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Form untuk Add Movement -->
                        <div id="addMovementSection" style="display: none;" class="mb-4">
                            <div class="border rounded p-3 bg-light">
                                <h5 class="text-<?= $current_type === 'IN' ? 'success' : 'warning' ?>">
                                    <i class="fas fa-<?= $current_type === 'IN' ? 'download' : 'upload' ?> me-2"></i>
                                    Form <?= $current_type === 'IN' ? 'Barang Masuk' : 'Barang Keluar' ?>
                                </h5>

                                <?= form_open('/stock/' . strtolower($current_type) . '/store', ['id' => 'movementForm']) ?>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nomor Referensi</label>
                                        <input type="text" class="form-control" name="reference_no"
                                            placeholder="Auto generate jika kosong">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Catatan Global</label>
                                        <input type="text" class="form-control" name="global_notes"
                                            placeholder="Catatan untuk semua item">
                                    </div>
                                </div>

                                <div id="movementItems">
                                    <!-- Items akan ditambahkan di sini via JavaScript -->
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-outline-primary" onclick="addMovementItem()">
                                            <i class="fas fa-plus me-2"></i>Tambah Item
                                        </button>
                                        <button type="submit" class="btn btn-<?= $current_type === 'IN' ? 'success' : 'warning' ?> ms-2">
                                            <i class="fas fa-save me-2"></i>Simpan Pergerakan
                                        </button>
                                        <button type="button" class="btn btn-secondary ms-2" onclick="hideAddModal()">
                                            <i class="fas fa-times me-2"></i>Batal
                                        </button>
                                    </div>
                                </div>
                                <?= form_close() ?>
                            </div>
                        </div>

                        <!-- Recent Movements Table -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>No. Referensi</th>
                                        <th>Produk</th>
                                        <th>Kategori</th>
                                        <th>Jumlah</th>
                                        <th>Catatan</th>
                                        <th>User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($recent_movements)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                                Belum ada data pergerakan untuk filter yang dipilih
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($recent_movements as $movement): ?>
                                            <tr>
                                                <td>
                                                    <small class="text-muted">
                                                        <?= date('d/m/Y H:i', strtotime($movement['created_at'])) ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?= $movement['type'] === 'IN' ? 'success' : 'warning' ?>">
                                                        <?= esc($movement['reference_no']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong><?= esc($movement['product_name']) ?></strong>
                                                        <br><small class="text-muted"><?= esc($movement['product_sku']) ?></small>
                                                    </div>
                                                </td>
                                                <td><?= esc($movement['category_name']) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $movement['type'] === 'IN' ? 'success' : 'danger' ?> fs-6">
                                                        <?= $movement['type'] === 'IN' ? '+' : '-' ?><?= number_format($movement['quantity']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <small><?= esc($movement['notes'] ?: '-') ?></small>
                                                </td>
                                                <td>
                                                    <small class="text-muted"><?= esc($movement['created_by']) ?></small>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if (!empty($recent_movements) && count($recent_movements) >= 10): ?>
                            <div class="text-center mt-3">
                                <a href="<?= base_url('stock/history') ?>?type=<?= $current_type ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i>Lihat Semua Riwayat
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let itemIndex = 0;
    const currentType = '<?= $current_type ?>';
    const searchEndpoint = '<?= base_url('api/products/search') ?>';
    const searchTimers = {};
    const searchCache = {};

    function showAddModal() {
        document.getElementById('addMovementSection').style.display = 'block';
        if (itemIndex === 0) {
            addMovementItem(); // Add first item automatically
        }
    }

    function hideAddModal() {
        document.getElementById('addMovementSection').style.display = 'none';
        document.getElementById('movementItems').innerHTML = '';
        itemIndex = 0;
        document.getElementById('movementForm').reset();
    }

    function addMovementItem() {
        const itemHtml = `
        <div class="row mb-3 movement-item" id="item-${itemIndex}">
            <div class="col-md-4">
                <label class="form-label">Produk</label>
                <input type="text" class="form-control product-search" id="product-search-${itemIndex}" placeholder="Ketik nama/kode barang..." autocomplete="off" oninput="handleSearchInput(${itemIndex}, this.value)">
                <input type="hidden" name="movements[${itemIndex}][product_id]" id="product-id-${itemIndex}" required>
                <div class="list-group product-suggestions mt-1 d-none" id="product-suggestions-${itemIndex}"></div>
                <small class="text-muted" id="product-meta-${itemIndex}"></small>
            </div>
            <div class="col-md-2">
                <label class="form-label">Jumlah</label>
                <input type="number" class="form-control" name="movements[${itemIndex}][quantity]" 
                       min="1" required id="quantity-${itemIndex}">
                <small class="text-muted" id="stock-info-${itemIndex}"></small>
            </div>
            <div class="col-md-4">
                <label class="form-label">Catatan</label>
                <input type="text" class="form-control" name="movements[${itemIndex}][notes]" 
                       placeholder="Catatan item">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger" onclick="removeMovementItem(${itemIndex})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

        document.getElementById('movementItems').insertAdjacentHTML('beforeend', itemHtml);
        itemIndex++;
    }

    function removeMovementItem(index) {
        const row = document.getElementById(`item-${index}`);
        if (row) {
            row.remove();
        }
    }

    function escapeHtml(text) {
        return String(text || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function hideSuggestions(index) {
        const box = document.getElementById(`product-suggestions-${index}`);
        if (!box) {
            return;
        }

        box.classList.add('d-none');
        box.innerHTML = '';
    }

    function applyProductSelection(index, product) {
        const productInput = document.getElementById(`product-search-${index}`);
        const productIdInput = document.getElementById(`product-id-${index}`);
        const qtyInput = document.getElementById(`quantity-${index}`);
        const stockInfo = document.getElementById(`stock-info-${index}`);
        const productMeta = document.getElementById(`product-meta-${index}`);

        if (!productInput || !productIdInput || !qtyInput || !stockInfo || !productMeta) {
            return;
        }

        const currentStock = parseInt(product.current_stock || 0, 10) || 0;
        const unit = product.unit || 'Pcs';

        productInput.value = `${product.name} (${product.sku})`;
        productIdInput.value = product.id;
        productMeta.textContent = `Kode: ${product.sku} | Satuan: ${unit}`;

        if (currentType === 'OUT') {
            stockInfo.textContent = `Stok tersedia: ${currentStock}`;
            qtyInput.max = currentStock;
            if (currentStock === 0) {
                stockInfo.className = 'text-danger';
                qtyInput.disabled = true;
            } else {
                stockInfo.className = 'text-muted';
                qtyInput.disabled = false;
            }
        } else {
            stockInfo.textContent = `Stok saat ini: ${currentStock}`;
            stockInfo.className = 'text-muted';
            qtyInput.max = '';
            qtyInput.disabled = false;
        }

        hideSuggestions(index);
    }

    function renderSuggestions(index, items) {
        const box = document.getElementById(`product-suggestions-${index}`);
        if (!box) {
            return;
        }

        if (!items || items.length === 0) {
            hideSuggestions(index);
            return;
        }

        box.innerHTML = items.map((item) => {
            const label = `${item.name} (${item.sku})`;
            const stockText = currentType === 'OUT' ? ` | Stok: ${item.current_stock}` : '';
            return `<button type="button" class="list-group-item list-group-item-action" onclick="selectProduct(${index}, ${item.id})">${escapeHtml(label + stockText)}</button>`;
        }).join('');
        box.classList.remove('d-none');
    }

    function selectProduct(index, productId) {
        const key = String(productId);
        if (!searchCache[key]) {
            return;
        }

        applyProductSelection(index, searchCache[key]);
    }

    async function handleSearchInput(index, keyword) {
        const value = String(keyword || '').trim();
        const productIdInput = document.getElementById(`product-id-${index}`);
        if (productIdInput) {
            productIdInput.value = '';
        }

        if (value.length < 2) {
            hideSuggestions(index);
            return;
        }

        clearTimeout(searchTimers[index]);
        searchTimers[index] = setTimeout(async () => {
            try {
                const response = await fetch(`${searchEndpoint}?q=${encodeURIComponent(value)}&limit=10`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const json = await response.json();
                const items = (json && json.status && Array.isArray(json.data)) ? json.data : [];

                items.forEach((item) => {
                    searchCache[String(item.id)] = item;
                });

                renderSuggestions(index, items);
            } catch (error) {
                hideSuggestions(index);
            }
        }, 250);
    }

    // Auto-hide alerts
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.movement-item')) {
                document.querySelectorAll('.product-suggestions').forEach((el) => {
                    el.classList.add('d-none');
                    el.innerHTML = '';
                });
            }
        });

        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (alert.classList.contains('alert-success')) {
                    setTimeout(() => alert.remove(), 3000);
                }
            });
        }, 100);
    });
</script>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .movement-item .col-md-4 {
        position: relative;
    }

    .product-suggestions {
        position: absolute;
        top: 100%;
        left: 12px;
        right: 12px;
        z-index: 1050;
        max-height: 240px;
        overflow-y: auto;
    }
</style>
<?= $this->endSection() ?>