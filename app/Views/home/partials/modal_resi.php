<?php
$bukaModalResi = session()->has('reference_no');
$referenceNo   = session('reference_no');
$namaPemohon   = session('borrower_name');
?>

<!-- Modal: Berhasil -->
<div class="modal fade" id="modalKodeResi" tabindex="-1" aria-labelledby="judulModalResi" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center pb-5">
                <div class="mb-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                </div>
                <h4 class="mb-3" id="judulModalResi" style="font-weight: 700; color: var(--primary-color);">Permintaan Berhasil!</h4>
                <p class="text-muted mb-4">Terima kasih <strong><?= esc($namaPemohon) ?></strong>, permintaan Anda telah kami terima.</p>
                
                <div class="card bg-light border-0 mb-4 mx-auto" style="max-width: 300px;">
                    <div class="card-body">
                        <p class="text-uppercase small text-muted mb-1 fw-bold">KODE RESI ANDA</p>
                        <h3 class="font-accent text-primary mb-0 user-select-all" style="letter-spacing: 1px;">
                            <?= esc($referenceNo) ?>
                        </h3>
                    </div>
                </div>
                
                <div class="alert alert-info border-0 rounded-3 text-start small mb-4" role="alert">
                    <div class="d-flex">
                        <i class="bi bi-info-circle-fill me-3 mt-1 fs-5"></i>
                        <div>
                            Harap simpan kode resi di atas. Anda akan membutuhkannya untuk melacak status permintaan atau saat mengambil barang.
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary fw-bold" onclick="salinResi('<?= esc($referenceNo) ?>', this)">
                        <i class="bi bi-clipboard me-2"></i>Salin Kode Resi
                    </button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

