<?php
$bukaModalCekStatus = (bool) session('_open_track_modal');
$pesanModalCekStatus = $bukaModalCekStatus ? session('error') : null;
?>

<!-- Modal: Cek Status Permintaan -->
<div class="modal fade" id="modalCekStatus" tabindex="-1" aria-labelledby="judulModalCekStatus" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="judulModalCekStatus">
                    <i class="bi bi-search me-2" style="color:var(--primary-color);"></i>Cek Status Permintaan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Masukkan kode resi untuk melihat status permintaan Anda.</p>

                <?php if (!empty($pesanModalCekStatus)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-circle me-1"></i><?= esc($pesanModalCekStatus) ?>
                    </div>
                <?php endif ?>

                <form action="<?= base_url('track-status') ?>" method="POST" id="formCekStatusBeranda">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_from" value="home-modal">
                    <div class="mb-3">
                        <label class="form-label" for="kodeResiTrackBeranda">Kode Resi</label>
                        <input type="text" class="form-control" id="kodeResiTrackBeranda" name="reference_no"
                            value="<?= esc(old('reference_no', '')) ?>"
                            placeholder="Contoh: 20260414-102530" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" id="btnCekStatusBeranda">
                        <i class="bi bi-search me-1"></i>Cek Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
