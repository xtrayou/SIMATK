/* ask.js — Logika form public (ask.php) */
'use strict';

document.addEventListener('DOMContentLoaded', function() {
    // Tambah baris barang
    const btnAddItem = document.getElementById('add-item');
    if (btnAddItem) {
        btnAddItem.addEventListener('click', function() {
            const container = document.getElementById('item-container');
            const firstRow = container.querySelector('.item-row');
            const newRow = firstRow.cloneNode(true);

            newRow.querySelector('select').value = '';
            newRow.querySelector('input[type="number"]').value = 1;
            newRow.querySelector('.unit-label').textContent = 'Pcs';
            newRow.querySelector('.remove-item').classList.remove('disabled');

            container.appendChild(newRow);
            updateRemoveButtons();
        });
    }

    // Hapus baris
    const container = document.getElementById('item-container');
    if (container) {
        container.addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-item');
            if (!btn || btn.classList.contains('disabled')) return;
            const rows = document.querySelectorAll('.item-row');
            if (rows.length > 1) {
                btn.closest('.item-row').remove();
                updateRemoveButtons();
            }
        });

        // Update label satuan saat barang dipilih
        container.addEventListener('change', function(e) {
            if (e.target.classList.contains('select-product')) {
                const selected = e.target.options[e.target.selectedIndex];
                const unit = selected.dataset.unit || 'Pcs';
                e.target.closest('.item-row').querySelector('.unit-label').textContent = unit;
            }
        });
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.item-row');
        rows.forEach(function(row) {
            const btn = row.querySelector('.remove-item');
            if (rows.length <= 1) {
                btn.classList.add('disabled');
            } else {
                btn.classList.remove('disabled');
            }
        });
    }

    // Loading state saat submit
    const askForm = document.getElementById('askForm');
    if (askForm) {
        askForm.addEventListener('submit', function() {
            const btn = document.getElementById('btnSubmit');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
        });
    }
});
