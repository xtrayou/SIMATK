(function () {
    const filterStatus = document.getElementById('filterStatus');
    const perHalaman = document.getElementById('perHalaman');
    const kataKunci = document.getElementById('kataKunci');

    if (!filterStatus || !perHalaman) {
        return;
    }

    function terapkanFilter() {
        const url = new URL(window.location.href);
        const q = (kataKunci?.value || '').trim();
        const status = (filterStatus.value || '').trim();
        const perPage = (perHalaman.value || '').trim();

        if (q) {
            url.searchParams.set('q', q);
        } else {
            url.searchParams.delete('q');
        }

        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }

        if (perPage) {
            url.searchParams.set('per_page', perPage);
        } else {
            url.searchParams.delete('per_page');
        }

        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    filterStatus.addEventListener('change', terapkanFilter);
    perHalaman.addEventListener('change', terapkanFilter);
})();
