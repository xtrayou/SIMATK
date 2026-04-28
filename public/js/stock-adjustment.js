/* stock-adjustment.js — Logic form Penyesuaian Stok */
'use strict';

$(function () {
    var rowIndex = 0;

    function toggleView() {
        var count = $('#rows tr').length;
        if (count > 0) {
            $('#adjustmentTable, .action-buttons').removeClass('d-none');
            $('#empty-state').addClass('d-none');
        } else {
            $('#adjustmentTable, .action-buttons').addClass('d-none');
            $('#empty-state').removeClass('d-none');
        }
    }

    $('#btn-add-item').on('click', function () {
        var picker = $('#productPicker');
        var opt    = picker.find('option:selected');
        var pid    = picker.val();
        if (!pid) return;
        if ($('.row-item[data-id="' + pid + '"]').length > 0) { alert('Barang sudah ada di daftar.'); return; }

        var name = opt.data('name'), sku = opt.data('sku'), stock = opt.data('stock'), unit = opt.data('unit');
        var row =
            '<tr class="row-item" data-id="' + pid + '">' +
            '<td><input type="hidden" name="adjustments[' + rowIndex + '][product_id]" value="' + pid + '">' +
            '<div class="fw-bold text-primary">' + name + '</div><code class="small text-muted">' + sku + '</code></td>' +
            '<td class="text-center fw-bold text-muted">' + stock + ' ' + unit + '</td>' +
            '<td><div class="input-group input-group-sm">' +
            '<input type="number" name="adjustments[' + rowIndex + '][new_stock]" class="form-control text-center physical-qty" value="' + stock + '" min="0">' +
            '<span class="input-group-text">' + unit + '</span></div>' +
            '<input type="hidden" name="adjustments[' + rowIndex + '][notes]" class="row-notes"></td>' +
            '<td class="text-center"><span class="diff-badge badge bg-secondary">0</span></td>' +
            '<td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button></td></tr>';

        $('#rows').append(row);
        rowIndex++;
        picker.val('').trigger('change');
        toggleView();
    });

    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
        toggleView();
    });

    $(document).on('input', '.physical-qty', function () {
        var row = $(this).closest('tr');
        var physical = parseInt($(this).val()) || 0;
        var system   = parseInt($('#productPicker').find('option[value="' + row.data('id') + '"]').data('stock')) || 0;
        var diff     = physical - system;
        var badge    = row.find('.diff-badge');
        badge.text(diff > 0 ? '+' + diff : diff)
             .removeClass('bg-secondary bg-success bg-danger')
             .addClass(diff === 0 ? 'bg-secondary' : (diff > 0 ? 'bg-success' : 'bg-danger'));
    });

    $('#adjustmentForm').on('submit', function () {
        if (!confirm('Yakin ingin menerapkan penyesuaian stok? Tindakan ini tidak dapat dibatalkan.')) return false;
        $('#btn-submit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');
    });
});
