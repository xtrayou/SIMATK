$(document).ready(function () {
    const config = window.productFormConfig || {};
    const kodeBarangApiUrl = config.kodeBarangApiUrl || '/api/kode-barang';
    const submitText = config.submitText || 'Menyimpan...';
    const hasPreview =
        $('#preview-name').length > 0 &&
        $('#preview-stock').length > 0 &&
        $('#preview-unit').length > 0 &&
        $('#preview-category').length > 0;

    function updatePreview() {
        $('#preview-name').text($('#name').val() || 'Nama Barang');
        $('#preview-stock').text(parseInt($('#initial_stock').val(), 10) || 0);
        $('#preview-unit').text($('#unit').val() || 'Pcs');
        $('#preview-category').text($('#category_id option:selected').text() || 'Kategori');
    }

    function autoSelectCategory(kode, data) {
        if (!kode || kode.length < 3) {
            return;
        }

        const parentKode = kode.substring(0, kode.length - 3) + '000';
        const parentMatch = data.find((item) => item.kode === parentKode);
        if (!parentMatch) {
            return;
        }

        const parentName = (parentMatch.nama || '').trim().toUpperCase();
        $('#category_id option').each(function () {
            if ($(this).text().trim().toUpperCase() === parentName) {
                $(this).prop('selected', true).trigger('change');
            }
        });
    }

    $.getJSON(kodeBarangApiUrl, function (data) {
        $('#kode_barang_list, #nama_barang_list').remove();

        const skuList = $('<datalist id="kode_barang_list"></datalist>');
        const nameList = $('<datalist id="nama_barang_list"></datalist>');

        data.forEach((item) => {
            skuList.append(`<option value="${item.kode}">${item.nama}</option>`);
            nameList.append(`<option value="${item.nama}">${item.kode}</option>`);
        });

        $('body').append(skuList).append(nameList);

        $('#sku').on('change', function () {
            const val = $(this).val().trim().toLowerCase();
            if (!val) return;
            
            const matched = data.find((item) => item.kode.toLowerCase() === val || item.nama.toLowerCase() === val);
            if (!matched) {
                return;
            }

            $(this).val(matched.kode); // Set input to the actual code
            if ($('#name').val() === '') {
                $('#name').val(matched.nama).trigger('input');
            }

            autoSelectCategory(matched.kode, data);
        });

        $('#name').on('change', function () {
            const val = $(this).val().trim().toLowerCase();
            if (!val) return;
            
            const matched = data.find((item) => item.nama.toLowerCase() === val || item.kode.toLowerCase() === val);
            if (!matched) {
                return;
            }

            $(this).val(matched.nama); // Set input to the actual name
            if ($('#sku').val() === '') {
                $('#sku').val(matched.kode).trigger('change');
            }

            autoSelectCategory(matched.kode, data);
        });
    }).fail(function () {
        console.warn('Gagal memuat data kode barang dari API');
    });

    if (hasPreview) {
        $('#name, #initial_stock, #unit, #category_id').on('input change', updatePreview);
        updatePreview();
    }

    $('#productForm').on('submit', function () {
        $('#btnSubmit')
            .prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm me-2"></span>' + submitText);
    });
});
