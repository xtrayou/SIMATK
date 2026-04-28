/* permintaan-form.js — Logika form permintaan internal */
'use strict';

$(document).ready(function() {
    // Tambah Baris
    $('#add-item').on('click', function() {
        const firstRow = $('.item-row').first();
        const newRow = firstRow.clone();

        newRow.find('select').val('');
        newRow.find('input').val(1);
        newRow.find('.unit-label').text('Pcs');
        newRow.find('.remove-item').removeClass('disabled');

        $('#item-container').append(newRow);
        updateRemoveButtons();
    });

    // Hapus Baris
    $(document).on('click', '.remove-item', function() {
        if ($('.item-row').length > 1) {
            $(this).closest('.item-row').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        if ($('.item-row').length <= 1) {
            $('.remove-item').addClass('disabled');
        } else {
            $('.remove-item').removeClass('disabled');
        }
    }

    // Update Label Satuan
    $(document).on('change', '.select-product', function() {
        const unit = $(this).find('option:selected').data('unit') || 'Pcs';
        $(this).closest('.item-row').find('.unit-label').text(unit);
    });

    // Form Loading
    $('#requestForm').on('submit', function() {
        $('#btnSubmit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...');
    });
});
