/* stock-in.js — Logic form Barang Masuk
   Endpoint URL diinjeksi dari view via window.SIMATK_IN = { searchUrl, autofillUrl } */
'use strict';

$(function () {
    var cfg             = window.SIMATK_IN || {};
    var searchEndpoint  = cfg.searchUrl   || '';
    var autofillEndpoint = cfg.autofillUrl || '';
    var rowIndex        = 1;
    var suggestionCache = {};
    var autofillCache   = {};

    function escHtml(text) {
        return String(text || '')
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function ensureSuggestionBox($row) {
        var $box = $row.find('.autocomplete-menu');
        if ($box.length === 0) {
            $box = $('<div class="autocomplete-menu list-group shadow-sm"></div>');
            $row.find('.product-autofill').after($box);
        }
        return $box;
    }

    function hideSuggestions($row) { $row.find('.autocomplete-menu').hide().empty(); }

    function applyProductData($row, data) {
        var $select = $row.find('.select-barang');
        var $opt    = $select.find('option[value="' + data.id + '"]');
        var label   = (data.nama_barang || data.name) + ' (' + (data.kode_barang || data.sku) + ')';
        var unit    = data.satuan || data.unit || 'Pcs';
        var stok    = data.stok != null ? data.stok : (data.current_stock || 0);

        if ($opt.length === 0) {
            $opt = $('<option>').val(data.id).attr('data-unit', unit).attr('data-stock', stok).text(label);
            $select.append($opt);
        } else {
            $opt.attr('data-unit', unit).attr('data-stock', stok);
        }
        $select.val(String(data.id)).trigger('change');
        $row.find('.product-autofill').val(label);
        $row.find('.autofill-hint').text('Autofill berhasil.');
    }

    function renderSuggestions($row, items) {
        var $box = ensureSuggestionBox($row);
        if (!items || items.length === 0) { hideSuggestions($row); return; }
        var html = '';
        items.forEach(function (item) {
            html += '<button type="button" class="list-group-item list-group-item-action autocomplete-item"' +
                ' data-id="' + item.id + '" data-name="' + escHtml(item.name) + '"' +
                ' data-sku="' + escHtml(item.sku) + '" data-unit="' + escHtml(item.unit || 'Pcs') + '"' +
                ' data-stock="' + (item.current_stock || 0) + '">' +
                '<div class="d-flex justify-content-between"><span class="fw-semibold">' + escHtml(item.name) + '</span>' +
                '<small class="text-muted">' + escHtml(item.sku) + '</small></div>' +
                '<small class="text-muted">Stok: ' + (item.current_stock || 0) + ' ' + escHtml(item.unit || 'Pcs') + '</small></button>';
        });
        $box.html(html).show();
    }

    function fetchSuggestions($row, keyword) {
        var value = (keyword || '').trim();
        if (value.length < 2) { hideSuggestions($row); return; }
        var key = value.toLowerCase();
        if (suggestionCache[key]) { renderSuggestions($row, suggestionCache[key]); return; }
        var prev = $row.data('suggestXhr');
        if (prev && prev.readyState !== 4) prev.abort();
        var xhr = $.ajax({
            url: searchEndpoint, method: 'GET', dataType: 'json', data: { q: value, limit: 8 },
            success: function (res) {
                var items = (res && res.status && Array.isArray(res.data)) ? res.data : [];
                suggestionCache[key] = items;
                renderSuggestions($row, items);
            },
            error: function (jq) { if (jq.statusText !== 'abort') hideSuggestions($row); }
        });
        $row.data('suggestXhr', xhr);
    }

    function fetchAutofill($row, keyword) {
        var value = (keyword || '').trim();
        if (value.length < 3) { $row.find('.autofill-hint').text(''); return; }
        var key = value.toLowerCase();
        if (autofillCache[key]) { applyProductData($row, autofillCache[key]); return; }
        var payload = /^\d+$/.test(value) ? { kode: value } : { nama: value };
        var prev = $row.data('autofillXhr');
        if (prev && prev.readyState !== 4) prev.abort();
        var xhr = $.ajax({
            url: autofillEndpoint, method: 'GET', dataType: 'json', data: payload,
            success: function (res) {
                if (!res || !res.status || !res.data) {
                    $row.find('.autofill-hint').text('Barang tidak ditemukan.'); return;
                }
                autofillCache[key] = res.data;
                applyProductData($row, res.data);
                hideSuggestions($row);
            },
            error: function (jq) {
                if (jq.statusText !== 'abort') $row.find('.autofill-hint').text('Data barang tidak ditemukan.');
            }
        });
        $row.data('autofillXhr', xhr);
    }

    function updateTotals() {
        var totalQty = 0, count = 0;
        $('.item-row').each(function () {
            var qty     = parseInt($(this).find('.quantity-input').val())  || 0;
            var damaged = parseInt($(this).find('.damaged-input').val())   || 0;
            var pid     = $(this).find('.select-barang').val();
            if (pid && (qty > 0 || damaged > 0)) { totalQty += qty + damaged; count++; }
        });
        $('#total-qty').text(totalQty.toLocaleString());
        $('#item-count').text(count + ' item terpilih');
    }

    // ── Event Listeners ──────────────────────────────────────
    $('#btn-add').on('click', function () {
        var row = $('.item-row').first().clone();
        row.find('.product-autofill').val('');
        row.find('.select-barang').attr('name', 'movements[' + rowIndex + '][product_id]').val('');
        row.find('.quantity-input').attr('name', 'movements[' + rowIndex + '][quantity]').val('');
        row.find('.damaged-input').attr('name', 'movements[' + rowIndex + '][damaged_quantity]').val('0');
        row.find('.unit-label').text('Pcs');
        row.find('.autofill-hint').text('');
        row.find('.stock-info').text('Stok saat ini: -');
        row.find('.remove-row').removeClass('disabled');
        row.find('.autocomplete-menu').remove();
        $('#rows').append(row);
        rowIndex++;
        updateTotals();
    });

    $(document).on('click', '.remove-row', function () {
        if ($('.item-row').length > 1) { $(this).closest('.item-row').remove(); updateTotals(); }
    });

    $(document).on('change', '.select-barang', function () {
        var opt = $(this).find('option:selected');
        var row = $(this).closest('.item-row');
        row.find('.stock-info').text('Stok saat ini: ' + (opt.data('stock') || 0) + ' ' + (opt.data('unit') || 'Pcs'));
        row.find('.unit-label').text(opt.data('unit') || 'Pcs');
        if (opt.val()) row.find('.product-autofill').val(opt.text().trim());
        updateTotals();
    });

    $(document).on('input', '.product-autofill', function () {
        var input = this, $row = $(input).closest('.item-row');
        $row.find('.autofill-hint').text('');
        clearTimeout(input._timer);
        input._timer = setTimeout(function () { fetchSuggestions($row, $(input).val()); }, 250);
    });

    $(document).on('keydown', '.product-autofill', function (e) {
        if (e.key !== 'Enter') return;
        e.preventDefault();
        var $row   = $(this).closest('.item-row');
        var $first = $row.find('.autocomplete-item').first();
        if ($first.length) { $first.trigger('click'); return; }
        fetchAutofill($row, $(this).val());
    });

    $(document).on('blur', '.product-autofill', function () {
        var input = this, $row = $(input).closest('.item-row');
        setTimeout(function () { hideSuggestions($row); fetchAutofill($row, $(input).val()); }, 150);
    });

    $(document).on('click', '.autocomplete-item', function () {
        var $item = $(this), $row = $item.closest('.item-row');
        applyProductData($row, {
            id: parseInt($item.data('id'), 10),
            nama_barang: $item.data('name'), kode_barang: $item.data('sku'),
            satuan: $item.data('unit'), stok: parseInt($item.data('stock'), 10) || 0
        });
        hideSuggestions($row);
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.item-row').length) $('.autocomplete-menu').hide();
    });

    $(document).on('input', '.quantity-input, .damaged-input', function () { updateTotals(); });

    $('#stockInForm').on('submit', function () {
        $('#btn-submit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');
    });

    $('.select-barang').trigger('change');
});
