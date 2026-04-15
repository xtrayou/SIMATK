<?php
$daftarKategori = $daftarKategori ?? [];
$kataKunci = (string) ($kataKunci ?? '');
$nomorAwal = (int) ($nomorAwal ?? 1);
$totalData = (int) ($totalData ?? count($daftarKategori));

$statusBadge = $statusBadge ?? static function ($isActive): string {
    return $isActive
        ? '<span class="badge bg-success">Aktif</span>'
        : '<span class="badge bg-secondary">Nonaktif</span>';
};

$formatTanggal = $formatTanggal ?? static function ($date): string {
    $timestamp = strtotime((string) $date);
    return $timestamp ? date('d M Y', $timestamp) : '-';
};

$shortText = $shortText ?? static function ($text, int $limit = 60): string {
    return mb_strimwidth((string) $text, 0, $limit, '...');
};
?>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Barang</th>
                <th>Status</th>
                <th>Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarKategori)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <?= !empty($kataKunci)
                            ? 'Tidak ada hasil pencarian'
                            : 'Belum ada data kategori' ?>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($daftarKategori as $i => $item): ?>
                    <tr>
                        <td><?= $nomorAwal + $i ?></td>

                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-isi bg-primary text-white">
                                    <i class="bi bi-collection-fill"></i>
                                </div>
                                <?= esc($item['name']) ?>
                            </div>
                        </td>

                        <td class="text-muted">
                            <?= !empty($item['description'])
                                ? esc($shortText($item['description']))
                                : 'Tidak ada deskripsi' ?>
                        </td>

                        <td>
                            <span class="badge bg-light text-dark border">
                                <?= $item['jumlah_barang'] ?? 0 ?>
                            </span>
                        </td>

                        <td>
                            <?= $statusBadge((bool) $item['is_active']) ?>
                        </td>

                        <td class="text-muted small">
                            <?= $formatTanggal($item['created_at']) ?>
                        </td>

                        <td>
                            <div class="d-flex gap-1">
                                <a href="<?= base_url('/categories/edit/' . $item['id']) ?>"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <button class="btn btn-sm btn-outline-danger btn-hapus"
                                    data-id="<?= $item['id'] ?>"
                                    data-nama="<?= esc($item['name']) ?>"
                                    data-jumlah="<?= $item['jumlah_barang'] ?? 0 ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (!empty($paginasi)): ?>
    <div class="d-flex justify-content-between align-items-center mt-3">
        <small class="text-muted">
            Menampilkan <?= $nomorAwal ?> – <?= min($nomorAwal + count($daftarKategori) - 1, $totalData) ?>
            dari <?= $totalData ?>
        </small>
        <?= $paginasi ?>
    </div>
<?php endif; ?>