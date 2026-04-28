/* stock-out.js — Logic form Barang Keluar */
'use strict';

$(function () {
    var rowIndex = 1;

    function checkStock(row) {
        var qty   = parseInt(row.find('.quantity-input').val()) || 0;
        var stock = parseInt(row.find('.select-barang option:selected').data('stock')) || 0;
        if (qty > stock) {
            row.find('.quantity-input').addClass('is-invalid');
            row.find('.over-stock-msg').removeClass('d-none');
        } else {
            row.find('.quantity-input').removeClass('is-invalid');
            row.find('.over-stock-msg').addClass('d-none');
        }
    }

    function updateTotals() {
        var totalQty = 0, count = 0;
        $('.item-row').each(function () {
            var qty = parseInt($(this).find('.quantity-input').val()) || 0;
            var pid = $(this).find('.select-barang').val();
            if (pid && qty > 0) { totalQty += qty; count++; }
        });
        $('#total-qty').text(totalQty.toLocaleString());
        $('#item-count').text(count + ' item terpilih');
    }

    $('#btn-add').on('click', function () {
        var row = $('.item-row').first().clone();
        row.find('select').attr('name', 'movements[' + rowIndex + '][product_id]').val('');
        row.find('input').attr('name', 'movements[' + rowIndex + '][quantity]').val('');
        row.find('.unit-label').text('Pcs');
        row.find('.stock-info').text('-');
        row.find('.over-stock-msg').addClass('d-none');
        row.find('.remove-row').removeClass('disabled');
        $('#rows').append(row);
        rowIndex++;
        updateTotals();
    });

    $(document).on('click', '.remove-row', function () {
        if ($('.item-row').length > 1) { $(this).closest('.item-row').remove(); updateTotals(); }
    });

    $(document).on('change', '.select-barang', function () {
        var opt  = $(this).find('option:selected');
        var row  = $(this).closest('.item-row');
        row.find('.stock-info').text(opt.data('stock') || 0);
        row.find('.unit-label').text(opt.data('unit') || 'Pcs');
        row.find('.quantity-input').attr('max', opt.data('stock') || 0);
        checkStock(row);
        updateTotals();
    });

    $(document).on('input', '.quantity-input', function () {
        checkStock($(this).closest('.item-row'));
        updateTotals();
    });

    $('#stockOutForm').on('submit', function (e) {
        if ($('.is-invalid').length > 0) {
            e.preventDefault();
            alert('Ada jumlah barang yang melebihi stok tersedia!');
            return false;
        }
        $('#btn-submit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');
    });

    $('.select-barang').trigger('change');
});
