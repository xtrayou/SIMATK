/* ask-success.js — Logika halaman permintaan sukses */
'use strict';

// Copy receipt code to clipboard
function copyReceiptCode() {
    const receiptCode = document.getElementById('receiptCode').textContent.trim();
    navigator.clipboard.writeText(receiptCode).then(() => {
        // Tampil notifikasi sukses
        const btn = event.target.closest('.copy-btn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Tersalin!';
        btn.style.background = 'rgba(76, 175, 80, 0.3)';

        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.style.background = 'rgba(255, 255, 255, 0.2)';
        }, 2000);
    });
}

// Tampilkan pop-up sweet alert saat halaman dimuat
window.addEventListener('load', function() {
    Swal.fire({
        title: '✅ Permintaan Berhasil!',
        html: `
            <div style="text-align: left; font-size: 0.95rem;">
                <p><strong>Permohonan Anda telah diterima dan tercatat dalam sistem.</strong></p>
                <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin: 1rem 0; border-left: 4px solid #0d6efd;">
                    <p style="margin: 0 0 0.5rem 0; font-size: 0.85rem; color: #666;">Kode Resi:</p>
                    <p style="margin: 0; font-size: 1.2rem; font-weight: 700; color: #0d6efd; font-family: 'Courier New', monospace;">
                        ${document.getElementById('receiptCode').textContent.trim()}
                    </p>
                </div>
                <p><i class="bi bi-chat-dots me-1"></i> <strong>Admin akan membalas permintaan Anda via chat atau email dalam 1-2 hari kerja.</strong></p>
                <p style="color: #666; font-size: 0.9rem; margin-bottom: 0;">Simpan kode resi di atas untuk referensi Anda.</p>
            </div>
        `,
        icon: 'success',
        confirmButtonText: 'Saya Mengerti',
        confirmButtonColor: '#3B5BDB',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            // Posisikan pop-up di tengah layar
            Swal.getPopup().style.borderRadius = '16px';
        }
    });
});
