/* notifications.js — Logika notifikasi */
'use strict';

function deleteNotification(id) {
    if (!confirm('Hapus notifikasi ini?')) return;

    fetch(`${window.NOTIF_CFG.deleteUrl}/${id}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                [window.NOTIF_CFG.csrfName]: window.NOTIF_CFG.csrfHash
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status) {
                location.reload();
            }
        })
        .catch(err => console.error(err));
}
