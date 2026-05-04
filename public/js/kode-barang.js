$(function () {
    const $search = $('#q');
    const $rows = $('#kodeBarangTable tbody tr');

    $search.on('input', function () {
        const keyword = $(this).val().toLowerCase().trim();

        $rows.each(function () {
            const text = $(this).text().toLowerCase();
            const empty = $(this).find('td[colspan="3"]').length > 0;

            if (!empty) {
                $(this).toggle(text.includes(keyword));
            }
        });
    });
});

function resetForm() {
    document.getElementById('kode_id').value = '';
    document.getElementById('kode').value = '';
    document.getElementById('nama').value = '';
    document.getElementById('kodeBarangModalLabel').innerText = 'Tambah Kode Barang';
}
