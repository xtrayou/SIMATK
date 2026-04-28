/* dashboard.js — Logika untuk dashboard utama */
document.addEventListener('DOMContentLoaded', function () {

    // ── Grafik Pergerakan Stok (Line Chart) ──────────────────────
    const ctxPergerakan = document.getElementById('grafikPergerakanStok');
    let grafikPergerakan = null;
    if (ctxPergerakan) {
        grafikPergerakan = new Chart(ctxPergerakan.getContext('2d'), {
            type: 'line',
            data: {
                labels: window.DASHBOARD_CFG.chartLabels,
                datasets: [{
                    label: 'Barang Masuk',
                    data: window.DASHBOARD_CFG.chartIn,
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25,135,84,0.1)',
                    tension: 0.4, fill: true
                }, {
                    label: 'Barang Keluar',
                    data: window.DASHBOARD_CFG.chartOut,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220,53,69,0.1)',
                    tension: 0.4, fill: true
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: { y: { beginAtZero: true, ticks: { callback: function(v){ return v.toLocaleString('id-ID'); } } } },
                interaction: { intersect: false, mode: 'index' }
            }
        });
    }

    // ── Grafik Status Stok (Doughnut) ───────────────────────────
    const ctxStatus = document.getElementById('grafikStatusStok');
    let grafikStatus = null;
    if (ctxStatus) {
        grafikStatus = new Chart(ctxStatus.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: window.DASHBOARD_CFG.pieLabels,
                datasets: [{ data: window.DASHBOARD_CFG.pieData, backgroundColor: window.DASHBOARD_CFG.pieColors, borderWidth: 0, cutout: '70%' }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
    }

    // ── Pembaruan otomatis setiap 30 detik ──────────────────────
    setInterval(function() {
        fetch(window.DASHBOARD_CFG.apiStatsUrl)
            .then(function(r){ return r.json(); })
            .then(function(d){
                if (d.status) {
                    const el = document.getElementById('totalBarang');
                    if (el) el.textContent = d.stats.total_products.toLocaleString('id-ID');
                }
            })
            .catch(function(){ console.warn('Gagal memperbarui statistik dashboard'); });
    }, 30000);

    // ── Ekspor Grafik ────────────────────────────────────────────
    window.eksporGrafik = function (jenis) {
        var grafik, namaFile;
        if (jenis === 'pergerakan') { grafik = grafikPergerakan; namaFile = 'grafik-pergerakan-stok.png'; }
        else if (jenis === 'status') { grafik = grafikStatus; namaFile = 'grafik-status-stok.png'; }
        if (grafik) { var a = document.createElement('a'); a.download = namaFile; a.href = grafik.toBase64Image(); a.click(); }
    };

    // ── Notifikasi stok rendah ───────────────────────────────────
    if (window.DASHBOARD_CFG.lowStockCount > 0) {
        if ('Notification' in window) {
            Notification.requestPermission().then(function(izin) {
                if (izin === 'granted') {
                    setInterval(function() {
                        new Notification('Peringatan Inventori', {
                            body: window.DASHBOARD_CFG.lowStockCount + ' barang memiliki stok rendah',
                            icon: window.DASHBOARD_CFG.faviconUrl
                        });
                    }, 300000);
                }
            });
        }
    }
});
