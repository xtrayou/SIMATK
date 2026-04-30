/* permintaan-show.js — Logika aksi permintaan ATK */
'use strict';

function showAlert(message, type = 'success') {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    // Insert alert at top of first card
    const firstCard = document.querySelector('.card');
    if (firstCard) {
        firstCard.insertAdjacentHTML('afterbegin', alertHtml);
    }

    // Auto dismiss after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach((el) => {
            el.classList.remove('show');
            setTimeout(() => el.remove(), 500);
        });
    }, 5000);
}

async function jalankanAksi(url, pesanCek, tombol) {
    if (confirm(pesanCek)) {
        const btn = tombol;
        if (!btn) return;
        const originalHtml = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

        const reasonEl = document.getElementById('admin_reason');
        const reason = reasonEl ? reasonEl.value : '';

        const payload = new URLSearchParams();
        payload.append(window.SIMATK_REQ_URL.csrf_token, window.SIMATK_REQ_URL.csrf_hash);
        payload.append('reason', reason);

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: payload.toString(),
                credentials: 'same-origin',
            });

            const contentType = response.headers.get('content-type') || '';
            const isJson = contentType.includes('application/json');
            const data = isJson ? await response.json() : {};

            if (response.ok && data.success) {
                showAlert(data.message || 'Aksi berhasil diproses.', 'success');
                setTimeout(() => location.reload(), 1500);
                return;
            }

            showAlert((data && data.message) ? data.message : 'Terjadi kesalahan server.', 'danger');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        } catch (error) {
            console.error(error);
            showAlert('Terjadi kesalahan jaringan atau server.', 'danger');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('btn-approve')?.addEventListener('click', function() {
        jalankanAksi(window.SIMATK_REQ_URL.approve, 'Setujui permintaan ini?', this);
    });

    document.getElementById('btn-distribute')?.addEventListener('click', function() {
        jalankanAksi(window.SIMATK_REQ_URL.distribute, 'Lanjutkan distribusi? Tindakan ini akan memotong stok barang.', this);
    });

    document.getElementById('btn-cancel')?.addEventListener('click', function() {
        const reasonEl = document.getElementById('admin_reason');
        const reason = reasonEl ? reasonEl.value.trim() : '';

        if (!reason) {
            const userReason = prompt('Silakan berikan alasan pembatalan (Wajib):');
            if (userReason === null) return; // User cancelled prompt
            if (userReason.trim() === '') {
                alert('Alasan pembatalan wajib diisi.');
                return;
            }
            if (reasonEl) reasonEl.value = userReason;
        }

        jalankanAksi(window.SIMATK_REQ_URL.cancel, 'Apakah Anda yakin ingin membatalkan permintaan ini?', this);
    });
});
