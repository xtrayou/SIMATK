<?= $this->extend('layouts/app') ?>

<?php
$selected = static function ($value, $target): string {
    return (string) $value === (string) $target ? 'selected' : '';
};

$statusBadge = static function ($isActive): string {
    return $isActive
        ? '<span class="badge bg-success">Aktif</span>'
        : '<span class="badge bg-secondary">Nonaktif</span>';
};

$formatTanggal = static function ($date): string {
    $timestamp = strtotime((string) $date);
    return $timestamp ? date('d M Y', $timestamp) : '-';
};

$shortText = static function ($text, int $limit = 60): string {
    return mb_strimwidth((string) $text, 0, $limit, '...');
};
?>

<?= $this->section('content') ?>

<div class="container my-5">
    <div class="row">
        <div class="col-12">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Daftar Kategori</h1>
                <a href="<?= base_url('/categories/create') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Kategori
                </a>
            </div>

            <?= $this->include('kategori/partials/alerts') ?>

            <?= $this->include('kategori/partials/filter', [
                'kataKunci' => $kataKunci ?? '',
                'filterStatus' => $filterStatus ?? '',
                'perHalaman' => $perHalaman ?? 10,
                'selected' => $selected,
            ]) ?>

            <?= $this->include('kategori/partials/table', [
                'daftarKategori' => $daftarKategori,
                'kataKunci' => $kataKunci ?? '',
                'nomorAwal' => $nomorAwal,
                'totalData' => $totalData,
                'paginasi' => $paginasi,
                'statusBadge' => $statusBadge,
                'formatTanggal' => $formatTanggal,
                'shortText' => $shortText,
            ]) ?>

        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/kategori-index.js') ?>"></script>
<?= $this->endSection() ?>