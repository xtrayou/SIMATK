/* permintaan-index.js — Logika daftar permintaan */
'use strict';

$(document).ready(function() {
    if ($.fn.DataTable && $('#requestsTable').length) {
        $('#requestsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            order: [
                [0, 'desc']
            ]
        });
    }

    // Auto submit filter saat status dipilih agar langsung dieksekusi.
    const statusSelect = document.getElementById('statusFilterSelect');
    const filterForm = document.getElementById('requestFilterForm');
    if (statusSelect && filterForm) {
        statusSelect.addEventListener('change', function() {
            filterForm.submit();
        });
    }
});
