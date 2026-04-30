<?php
$bukaModalHasilTrack = (bool) session('_open_track_result_modal');
$dataHasilTrack = $bukaModalHasilTrack ? (array) (session('track_result_data') ?? []) : [];
$warnaStatus = in_array($dataHasilTrack['status_color'] ?? '', ['warning', 'info', 'success', 'danger', 'secondary'], true)
    ? $dataHasilTrack['status_color']
    : 'secondary';
?>

<!-- Modal: Hasil Cek Status -->
<div class="modal fade" id="modalHasilCekStatus" tabindex="-1" aria-labelledby="judulModalHasilCekStatus"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="judulModalHasilCekStatus">
                    <i class="bi bi-clipboard-data me-2" style="color:var(--primary-color);"></i>Hasil Cek Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <?php
                        $baris = [
                            'Kode Resi' => $dataHasilTrack['reference_no'] ?? '-',
                            'Nomor Permintaan' => $dataHasilTrack['request_no'] ?? '-',
                            'Pemohon' => $dataHasilTrack['borrower_name'] ?? '-',
                            'Unit' => $dataHasilTrack['borrower_unit'] ?? '-',
                            'Tanggal Permintaan' => $dataHasilTrack['request_date'] ?? '-',
                        ];
                        foreach ($baris as $label => $nilai): ?>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small"><?= $label ?></span>
                                <strong><?= esc((string) $nilai) ?></strong>
                            </div>
                        <?php endforeach ?>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Status</span>
                            <span class="badge bg-<?= esc($warnaStatus) ?>">
                                <i
                                    class="bi bi-<?= esc((string) ($dataHasilTrack['status_icon'] ?? 'question-circle')) ?> me-1"></i>
                                <?= esc((string) ($dataHasilTrack['status_text'] ?? 'Tidak Diketahui')) ?>
                            </span>
                        </div>

                        <?php if (!empty($dataHasilTrack['status_reason'])): ?>
                            <div class="mt-3 p-2 bg-white rounded border small">
                                <span class="d-block fw-bold text-danger text-uppercase mb-1"
                                    style="font-size: 0.7rem;">Catatan:</span>
                                <span class="text-muted italic"><?= nl2br(esc($dataHasilTrack['status_reason'])) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <button type="button" class="btn btn-primary w-100 mt-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>