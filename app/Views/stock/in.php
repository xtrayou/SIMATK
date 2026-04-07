<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="row">
    <!-- Form Utama -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-arrow-down-circle text-success me-2"></i>Form Barang Masuk</h5>
                <p class="text-muted small mb-0">Input stok yang baru datang ke gudang / inventory</p>
            </div>
            <div class="card-body p-4">
                <form action="<?= base_url('/stock/in/store') ?>" method="POST" id="stockInForm">
                    <?= csrf_field() ?>
                    <?php
                    $redirectAfter = (string) service('request')->getGet('redirect');
                    if (!str_starts_with($redirectAfter, '/')) {
                        $redirectAfter = '';
                    }
                    ?>
                    <input type="hidden" name="_redirect" value="<?= esc($redirectAfter) ?>">

                    <div class="row mb-4 h-100 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-bold small">Nomor Referensi / DO</label>
                            <input type="text" class="form-control" name="reference_no" placeholder="Contoh: DO-001 atau PO-123">
                        </div>
                        <div class="col-md-7">
                            <label class="form-label fw-bold small">Catatan Global</label>
                            <input type="text" class="form-control" name="global_notes" placeholder="Keterangan singkat pengiriman barang">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="itemTable">
                            <thead class="bg-light small">
                                <tr>
                                    <th width="50%">Pilih Barang</th>
                                    <th width="30%">Jumlah Masuk</th>
                                    <th width="100" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="rows">
                                <tr class="item-row">
                                    <td>
                                        <select class="form-select select-produk" name="movements[0][product_id]" required>
                                            <option value="">- Cari Barang -</option>
                                            <?php foreach ($daftarProduk as $p): ?>
                                                <option value="<?= $p['id'] ?>" data-unit="<?= $p['unit'] ?>" data-stock="<?= $p['current_stock'] ?>"
                                                    <?= $produkTerpilih == $p['id'] ? 'selected' : '' ?>>
                                                    <?= esc($p['name']) ?> (<?= $p['sku'] ?>)
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                        <input type="text" class="form-control form-control-sm product-autofill mt-2" placeholder="Ketik kode atau nama barang..." autocomplete="off">
                                        <small class="text-info autofill-hint d-block mt-1"></small>
                                        <small class="text-muted stock-info d-block mt-1">Stok saat ini: -</small>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control quantity-input" name="movements[0][quantity]" min="1" placeholder="0" required>
                                            <span class="input-group-text small-text unit-label">Pcs</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-row disabled">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btn-add">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Baris
                        </button>
                        <button type="submit" class="btn btn-success px-5 fw-bold" id="btn-submit">
                            SIMPAN BARANG MASUK
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4 bg-light">
            <div class="card-body p-4 text-center">
                <h6 class="text-muted fw-bold text-uppercase small mb-3">Total Barang Masuk</h6>
                <h2 class="display-5 fw-bold text-success mb-0" id="total-qty">0</h2>
                <p class="text-muted mb-0" id="item-count">0 item terpilih</p>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>10 Input Terakhir</h6>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($riwayatTerakhir)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($riwayatTerakhir as $mut): ?>
                            <div class="list-group-item p-3 border-0 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-primary small"><?= esc($mut['product_name']) ?></span>
                                    <span class="badge bg-success small">+<?= number_format($mut['quantity']) ?></span>
                                </div>
                                <div class="d-flex justify-content-between small text-muted">
                                    <span><?= date('d/m/Y H:i', strtotime($mut['created_at'])) ?></span>
                                    <span>Ref: <?= esc($mut['reference_no']) ?: '-' ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="p-4 text-center text-muted italic">Belum ada riwayat masuk.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        let rowIndex = 1;
        const searchEndpoint = '<?= base_url('api/products/search') ?>';
        const autofillEndpoint = '<?= base_url('api/products/autofill') ?>';
        const suggestionCache = {};
        const autofillCache = {};

        function escHtml(text) {
            return String(text || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function ensureSuggestionBox($row) {
            let $box = $row.find('.autocomplete-menu');
            if ($box.length === 0) {
                $box = $('<div class="autocomplete-menu list-group shadow-sm"></div>');
                $row.find('.product-autofill').after($box);
            }
            return $box;
        }

        function hideSuggestions($row) {
            $row.find('.autocomplete-menu').hide().empty();
        }

        function applyProductData($row, data) {
            const $select = $row.find('.select-produk');
            let $opt = $select.find(`option[value="${data.id}"]`);

            if ($opt.length === 0) {
                const label = `${data.nama_barang || data.name} (${data.kode_barang || data.sku})`;
                $opt = $(`<option value="${data.id}" data-unit="${data.satuan || data.unit || 'Pcs'}" data-stock="${data.stok ?? data.current_stock ?? 0}">${label}</option>`);
                $select.append($opt);
            } else {
                $opt.attr('data-unit', data.satuan || data.unit || 'Pcs');
                $opt.attr('data-stock', data.stok ?? data.current_stock ?? 0);
            }

            $select.val(String(data.id)).trigger('change');
            $row.find('.product-autofill').val(`${data.nama_barang || data.name} (${data.kode_barang || data.sku})`);
            $row.find('.autofill-hint').text('Autofill berhasil.');
        }

        function renderSuggestions($row, items) {
            const $box = ensureSuggestionBox($row);

            if (!items || items.length === 0) {
                hideSuggestions($row);
                return;
            }

            let html = '';
            items.forEach(function(item) {
                html += `
                <button type="button" class="list-group-item list-group-item-action autocomplete-item"
                        data-id="${item.id}"
                        data-name="${escHtml(item.name)}"
                        data-sku="${escHtml(item.sku)}"
                        data-unit="${escHtml(item.unit || 'Pcs')}"
                        data-stock="${item.current_stock ?? 0}">
                    <div class="d-flex justify-content-between">
                        <span class="fw-semibold">${escHtml(item.name)}</span>
                        <small class="text-muted">${escHtml(item.sku)}</small>
                    </div>
                    <small class="text-muted">Stok: ${item.current_stock ?? 0} ${escHtml(item.unit || 'Pcs')}</small>
                </button>
            `;
            });

            $box.html(html).show();
        }

        function fetchSuggestions($row, keyword) {
            const value = (keyword || '').trim();
            if (value.length < 2) {
                hideSuggestions($row);
                return;
            }

            const cacheKey = value.toLowerCase();
            if (suggestionCache[cacheKey]) {
                renderSuggestions($row, suggestionCache[cacheKey]);
                return;
            }

            const prevXhr = $row.data('suggestXhr');
            if (prevXhr && prevXhr.readyState !== 4) {
                prevXhr.abort();
            }

            const xhr = $.ajax({
                url: searchEndpoint,
                method: 'GET',
                dataType: 'json',
                data: {
                    q: value,
                    limit: 8
                },
                success: function(res) {
                    const items = (res && res.status && Array.isArray(res.data)) ? res.data : [];
                    suggestionCache[cacheKey] = items;
                    renderSuggestions($row, items);
                },
                error: function(jqXHR) {
                    if (jqXHR.statusText !== 'abort') {
                        hideSuggestions($row);
                    }
                }
            });

            $row.data('suggestXhr', xhr);
        }

        function fetchAutofill($row, keyword) {
            const value = (keyword || '').trim();
            if (value.length < 3) {
                $row.find('.autofill-hint').text('');
                return;
            }

            const cacheKey = value.toLowerCase();
            if (autofillCache[cacheKey]) {
                applyProductData($row, autofillCache[cacheKey]);
                return;
            }

            const payload = /^\d+$/.test(value) ? {
                kode: value
            } : {
                nama: value
            };

            const prevXhr = $row.data('autofillXhr');
            if (prevXhr && prevXhr.readyState !== 4) {
                prevXhr.abort();
            }

            const xhr = $.ajax({
                url: autofillEndpoint,
                method: 'GET',
                dataType: 'json',
                data: payload,
                success: function(res) {
                    if (!res || !res.status || !res.data) {
                        $row.find('.autofill-hint').text('Barang tidak ditemukan.');
                        return;
                    }

                    const data = res.data;
                    autofillCache[cacheKey] = data;
                    applyProductData($row, data);
                    hideSuggestions($row);
                },
                error: function(jqXHR) {
                    if (jqXHR.statusText !== 'abort') {
                        $row.find('.autofill-hint').text('Data barang tidak ditemukan.');
                    }
                }
            });

            $row.data('autofillXhr', xhr);
        }

        // Tambah Baris
        $('#btn-add').on('click', function() {
            const row = $('.item-row').first().clone();
            row.find('.product-autofill').val('');
            row.find('.select-produk').attr('name', `movements[${rowIndex}][product_id]`).val('');
            row.find('.quantity-input').attr('name', `movements[${rowIndex}][quantity]`).val('');
            row.find('.unit-label').text('Pcs');
            row.find('.autofill-hint').text('');
            row.find('.stock-info').text('Stok saat ini: -');
            row.find('.remove-row').removeClass('disabled');
            row.find('.autocomplete-menu').remove();

            $('#rows').append(row);
            rowIndex++;
            updateTotals();
        });

        // Hapus Baris
        $(document).on('click', '.remove-row', function() {
            if ($('.item-row').length > 1) {
                $(this).closest('.item-row').remove();
                updateTotals();
            }
        });

        // Info Stok & Unit
        $(document).on('change', '.select-produk', function() {
            const opt = $(this).find('option:selected');
            const stock = opt.data('stock') || 0;
            const unit = opt.data('unit') || 'Pcs';

            const row = $(this).closest('.item-row');
            row.find('.stock-info').text(`Stok saat ini: ${stock} ${unit}`);
            row.find('.unit-label').text(unit);
            if (opt.val()) {
                row.find('.product-autofill').val(opt.text().trim());
            }
            updateTotals();
        });

        // Autofill by kode / nama with debounce
        $(document).on('input', '.product-autofill', function() {
            const input = this;
            const $row = $(input).closest('.item-row');
            $row.find('.autofill-hint').text('');
            clearTimeout(input._autofillTimer);
            input._autofillTimer = setTimeout(function() {
                fetchSuggestions($row, $(input).val());
            }, 250);
        });

        // Autofill when user presses Enter
        $(document).on('keydown', '.product-autofill', function(e) {
            if (e.key !== 'Enter') {
                return;
            }

            e.preventDefault();
            const $row = $(this).closest('.item-row');
            const $first = $row.find('.autocomplete-item').first();

            if ($first.length) {
                $first.trigger('click');
                return;
            }

            fetchAutofill($row, $(this).val());
        });

        // Autofill when user leaves the field
        $(document).on('blur', '.product-autofill', function() {
            const input = this;
            const $row = $(input).closest('.item-row');
            setTimeout(function() {
                hideSuggestions($row);
                fetchAutofill($row, $(input).val());
            }, 150);
        });

        // Pick suggestion item
        $(document).on('click', '.autocomplete-item', function() {
            const $item = $(this);
            const $row = $item.closest('.item-row');

            applyProductData($row, {
                id: parseInt($item.data('id'), 10),
                nama_barang: $item.data('name'),
                kode_barang: $item.data('sku'),
                satuan: $item.data('unit'),
                stok: parseInt($item.data('stock'), 10) || 0
            });

            hideSuggestions($row);
        });

        // Hide suggestions when clicking outside row
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.item-row').length) {
                $('.autocomplete-menu').hide();
            }
        });

        // Hitung Total
        $(document).on('input', '.quantity-input', function() {
            updateTotals();
        });

        function updateTotals() {
            let totalQty = 0;
            let count = 0;

            $('.item-row').each(function() {
                const qty = parseInt($(this).find('.quantity-input').val()) || 0;
                const pid = $(this).find('.select-produk').val();

                if (pid && qty > 0) {
                    totalQty += qty;
                    count++;
                }
            });

            $('#total-qty').text(totalQty.toLocaleString());
            $('#item-count').text(`${count} item terpilih`);
        }

        // Submit Loading
        $('#stockInForm').on('submit', function() {
            $('#btn-submit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');
        });

        // Init
        $('.select-produk').trigger('change');
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .small-text {
        font-size: 0.8rem;
    }

    .italic {
        font-style: italic;
    }

    .item-row td:first-child {
        position: relative;
    }

    .autocomplete-menu {
        position: absolute;
        left: 8px;
        right: 8px;
        top: 44px;
        z-index: 1050;
        max-height: 230px;
        overflow-y: auto;
        display: none;
    }
</style>
<?= $this->endSection() ?>
