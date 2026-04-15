<?php
$selected = static function ($value, $target): string {
    return (string) $value === (string) $target ? 'selected' : '';
};

$oldProductId = (string) old('product_id');
?>
<div class="col-lg-7">
    <div class="peminjaman-card">
        <form class="peminjaman-form" action="<?= base_url('ask/store') ?>" method="post" id="formPermintaan">
            <?= csrf_field() ?>
            <input type="hidden" name="_redirect" value="<?= current_url() . '#permintaan' ?>">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="namaPemohon" class="form-label">
                        <i class="bi bi-person me-1"></i>Nama Pemohon <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="namaPemohon" name="borrower_name"
                        value="<?= old('borrower_name') ?>"
                        placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="unitKerja" class="form-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" class="me-1" aria-hidden="true" style="margin-top:-2px;">
                            <path d="M6.5 15V1.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 .5.5V15h-2v-2h-2v2h-2zm-5 0V7.5a.5.5 0 0 1 .5-.5h3V15h-3zM2 8v1h2V8H2zm0 2v1h2v-1H2zm0 2v1h2v-1H2zm6-9v1h1V3H8zm2 0v1h1V3h-1zM8 5v1h1V5H8zm2 0v1h1V5h-1zM8 7v1h1V7H8zm2 0v1h1V7h-1zM8 9v1h1V9H8zm2 0v1h1V9h-1z" />
                        </svg>Unit Kerja / Prodi <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="unitKerja" name="borrower_unit" required>
                        <option value="">Pilih Unit Kerja</option>
                        <?php foreach ($unitKerja as $unit): ?>
                            <option value="<?= esc($unit) ?>" <?= $selected(old('borrower_unit'), $unit) ?>>
                                <?= esc($unit) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="emailPemohon" class="form-label">
                        <i class="bi bi-envelope me-1"></i>Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" class="form-control" id="emailPemohon" name="email"
                        value="<?= old('email') ?>"
                        placeholder="nama@fasilkom.ac.id" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nimNipPemohon" class="form-label">
                        <i class="bi bi-card-text me-1"></i>NIM / NIP
                    </label>
                    <input type="text" class="form-control" id="nimNipPemohon" name="borrower_identifier"
                        value="<?= old('borrower_identifier') ?>"
                        placeholder="Opsional">
                </div>
            </div>

            <div class="mb-3">
                <label for="filterKategori" class="form-label">
                    <i class="bi bi-tag me-1"></i>Filter Kategori Barang
                </label>
                <select class="form-select" id="filterKategori">
                    <option value="">Tampilkan Semua</option>
                    <?php foreach ($daftarKategori as $kategori): ?>
                        <option value="<?= $kategori['id'] ?>"><?= esc($kategori['name']) ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="barangDiminta" class="form-label">
                    <i class="bi bi-box-seam me-1"></i>Barang yang Diminta <span class="text-danger">*</span>
                </label>
                <input type="text"
                    class="form-control"
                    id="barangDiminta"
                    list="daftarBarangAutocomplete"
                    value="<?= esc($oldProductName ?? '') ?>"
                    placeholder="Ketik nama barang untuk cari otomatis..."
                    autocomplete="off"
                    required>
                <input type="hidden" id="barangDimintaId" name="product_id" value="<?= esc($oldProductId) ?>">
                <datalist id="daftarBarangAutocomplete">
                    <?php foreach ($daftarBarang as $barang): ?>
                        <option value="<?= esc($barang['name']) ?>"
                            data-id="<?= $barang['id'] ?>"
                            data-kategori="<?= $barang['category_id'] ?>"
                            data-stok="<?= $barang['current_stock'] ?>"
                            data-satuan="<?= esc($barang['unit']) ?>"
                            data-tersedia="<?= $barang['current_stock'] > 0 ? '1' : '0' ?>"
                            label="Status: <?= esc($barang['status_label'] ?? '') ?>">
                        </option>
                    <?php endforeach ?>
                </datalist>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="jumlahDiminta" class="form-label">
                        <i class="bi bi-123 me-1"></i>Jumlah <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-hash"></i></span>
                        <input type="number" class="form-control" id="jumlahDiminta" name="quantity"
                            value="<?= old('quantity', 1) ?>" min="1" placeholder="0" autocomplete="off" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tanggalPermintaan" class="form-label">
                        <i class="bi bi-calendar me-1"></i>Tanggal Permintaan <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control" id="tanggalPermintaan" name="request_date"
                        value="<?= old('request_date', date('Y-m-d')) ?>" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="keteranganPermintaan" class="form-label">
                    <i class="bi bi-card-text me-1"></i>Keperluan / Keterangan
                </label>
                <textarea class="form-control" id="keteranganPermintaan" name="notes"
                    rows="3" placeholder="Jelaskan keperluan permintaan barang..."><?= old('notes') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-submit w-100" id="btnAjukan">
                <i class="bi bi-send me-2"></i>Ajukan Permintaan
            </button>
        </form>
    </div>
</div>