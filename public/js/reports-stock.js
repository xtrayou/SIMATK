/* ============================================================
   reports-stock.js — Logic khusus halaman Laporan Stok
   Catatan: fungsi yang butuh data PHP (chart, printReport)
            tetap dipanggil dari view melalui window.SIMATK_STOCK
   ============================================================ */

'use strict';

/**
 * Helper: parse angka format Indonesia → integer
 * Contoh: "Rp 46.000"  → 46000
 *         "1.234.567"  → 1234567
 */
function parseIDNum(str) {
    return parseInt((str || '0').toString().replace(/\./g, '').replace(/[^0-9]/g, '')) || 0;
}

/**
 * Inisialisasi DataTable laporan stok
 */
function initStockTable() {
    if (typeof $.fn.DataTable === 'undefined') return;
    if (!$('#stockReportTable').length) return;

    $('#stockReportTable').DataTable({
        responsive: true,
        pageLength: 50,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        footerCallback: function(row, data, start, end, display) {
            var api = this.api();
            var intVal = function(i) {
                return typeof i === 'string'
                    ? i.replace(/[\$,]/g, '') * 1
                    : typeof i === 'number' ? i : 0;
            };
            api.column(4, { page: 'current' }).data().reduce(function(a, b) {
                return intVal(a) + intVal(b);
            }, 0);
        }
    });
}

/**
 * Auto-submit filter form saat dropdown berubah
 */
function initFilterAutoSubmit() {
    $('#category, #stock_status, #sort_by, #sort_order, #month, #year').on('change', function() {
        $('#filterForm').submit();
    });
}

/**
 * Export laporan ke Excel (mengikuti filter URL aktif)
 * baseExportUrl diinjeksi dari view via window.SIMATK_STOCK
 */
function exportReport(format) {
    var base = (window.SIMATK_STOCK || {}).exportBaseUrl || '';
    var params = new URLSearchParams(window.location.search);
    window.open(base + format + '?' + params.toString(), '_blank');
}

/**
 * Print laporan dengan layout resmi Stock Opname
 * periode (bulan, tahun) diinjeksi dari view via window.SIMATK_STOCK
 */
function printReport() {
    var cfg     = window.SIMATK_STOCK || {};
    var month   = new URLSearchParams(window.location.search).get('month') || cfg.currentMonth || '';
    var year    = new URLSearchParams(window.location.search).get('year')  || cfg.currentYear  || '';
    var bulanList = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    var namaBulan = bulanList[parseInt(month)] || '';
    var periode   = namaBulan + ' ' + year;

    var rows      = document.querySelectorAll('#stockReportTable tbody tr');
    var tableRows = '';
    var totalQty  = 0;
    var totalHarga = 0;

    rows.forEach(function(tr, idx) {
        var cells = tr.querySelectorAll('td');
        if (!cells.length) return;

        var namaBarang  = (cells[1]?.innerText.trim().split('\n')[0] || '-').trim();
        var stokRaw     = cells[4]?.querySelector('strong')?.innerText.trim()
                       || cells[4]?.innerText.trim().split('\n')[0];
        var jumlah      = parseIDNum(stokRaw);
        var nilaiLines  = (cells[7]?.innerText.trim() || '').split('\n');
        var nilaiTotal  = parseIDNum(nilaiLines[0]);
        var hargaLine   = nilaiLines.find(function(l) { return l.includes('@'); });
        var hargaSatuan = hargaLine
            ? parseIDNum(hargaLine)
            : (jumlah > 0 ? Math.round(nilaiTotal / jumlah) : 0);
        var baikQty     = cells[4]?.querySelector('.text-success')
            ? parseIDNum(cells[4].querySelector('.text-success').innerText)
            : jumlah;
        var rusakQty    = cells[4]?.querySelector('.text-danger')
            ? parseIDNum(cells[4].querySelector('.text-danger').innerText)
            : 0;

        totalQty   += jumlah;
        totalHarga += nilaiTotal;

        tableRows +=
            '<tr>' +
            '<td style="text-align:center">' + (idx + 1) + '</td>' +
            '<td>' + namaBarang + '</td>' +
            '<td style="text-align:center">' + jumlah.toLocaleString('id-ID') + '</td>' +
            '<td style="text-align:right">'  + (hargaSatuan > 0 ? Math.round(hargaSatuan).toLocaleString('id-ID') : '-') + '</td>' +
            '<td style="text-align:right">'  + (nilaiTotal  > 0 ? Math.round(nilaiTotal).toLocaleString('id-ID')  : '-') + '</td>' +
            '<td style="text-align:center">' + (baikQty  > 0 ? 'V' : '') + '</td>' +
            '<td style="text-align:center">' + (rusakQty > 0 ? rusakQty : '') + '</td>' +
            '</tr>';
    });

    tableRows +=
        '<tr style="font-weight:bold;border-top:2px solid #000">' +
        '<td colspan="3"></td>' +
        '<td style="text-align:right">' + (totalQty > 0 ? Math.round(totalHarga / totalQty).toLocaleString('id-ID') : '-') + '</td>' +
        '<td style="text-align:right">' + Math.round(totalHarga).toLocaleString('id-ID') + '</td>' +
        '<td colspan="2"></td>' +
        '</tr>';

    var tanggal = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });

    var html =
        '<!DOCTYPE html><html><head><meta charset="UTF-8">' +
        '<title>Stock Opname ' + periode + '</title>' +
        '<style>' +
        'body{font-family:Arial,sans-serif;font-size:9pt;margin:20px}' +
        '.hrow{margin-bottom:2px}.hrow span.lbl{display:inline-block;width:80px}' +
        '.ttl{text-align:center;font-weight:bold;font-size:11pt;margin:10px 0 4px}' +
        '.sub{text-align:center;font-size:9pt;margin-bottom:12px}' +
        'table{width:100%;border-collapse:collapse}' +
        'th{background:#f2f2f2;border:1px solid #000;padding:5px 4px;text-align:center;font-weight:bold}' +
        'td{border:1px solid #000;padding:3px 4px}' +
        '.sig{margin-top:28px}.sig table{border:none}.sig td{border:none;padding:2px}' +
        '@media print{button{display:none}}' +
        '</style></head><body>' +
        '<div class="hrow"><strong>LAMPIRAN BERITA ACARA STOK OPNAME FISIK PERSEDIAAN</strong></div>' +
        '<div class="hrow"><span class="lbl">Nomor</span>: ........../UN64.7/LK/' + new Date().getFullYear() + '</div>' +
        '<div class="hrow"><span class="lbl">Tanggal</span>: ' + tanggal + '</div>' +
        '<div class="hrow"><span class="lbl">Unit</span>: Fakultas Ilmu Komputer</div>' +
        '<div class="ttl">LAPORAN STOCK OPNAME</div>' +
        '<div class="sub">UNTUK PERIODE YANG BERAKHIR TANGGAL ' + periode.toUpperCase() + '</div>' +
        '<div class="sub">TAHUN ANGGARAN ' + year + '</div>' +
        '<table><thead>' +
        '<tr>' +
        '<th rowspan="2" width="5%">No</th>' +
        '<th rowspan="2" width="38%">Jenis Barang</th>' +
        '<th colspan="3">Hasil Stock Opname</th>' +
        '<th colspan="2">Kondisi Barang</th>' +
        '</tr><tr>' +
        '<th width="8%">Jumlah</th>' +
        '<th width="15%">Harga Satuan</th>' +
        '<th width="15%">Total Harga</th>' +
        '<th width="8%">Baik</th>' +
        '<th width="11%">Rusak/Usang</th>' +
        '</tr></thead><tbody>' + tableRows + '</tbody></table>' +
        '<div class="sig"><table width="100%">' +
        '<tr><td width="50%">Mengetahui,</td><td></td></tr>' +
        '<tr><td>A.n Dekan</td><td>Operator Persediaan</td></tr>' +
        '<tr><td>Wakil Dekan Bidang Umum dan Keuangan</td><td></td></tr>' +
        '<tr><td><br><br><br><br></td><td></td></tr>' +
        '<tr><td><u>Betha Nurina Sari, M.Kom.</u></td><td><u>M Rizki Fauzi S, S.Pd.</u></td></tr>' +
        '<tr><td>NIP. 198910232018032001</td><td></td></tr>' +
        '</table></div>' +
        '<script>window.onload=function(){window.print();}<\/script>' +
        '</body></html>';

    var w = window.open('', '_blank', 'width=900,height=700');
    w.document.write(html);
    w.document.close();
}

function initCategoryChart() {
    var cfg = window.SIMATK_STOCK || {};
    if (!cfg.hasChart) return;
    
    function formatCurrency(v) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(v));
    }
    const ctx = document.getElementById('categoryChart');
    if (!ctx) return;
    new Chart(ctx.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: cfg.chartLabels,
            datasets: [{
                data: cfg.chartData,
                backgroundColor: ['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF','#FF9F40','#C9CBCF'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            const pct   = ((ctx.parsed / total) * 100).toFixed(1);
                            return ctx.label + ': ' + formatCurrency(ctx.parsed) + ' (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });
}

/* Boot saat DOM siap */
$(function() {
    initStockTable();
    initFilterAutoSubmit();
    initCategoryChart();

    window.addEventListener('beforeprint', function() { document.body.classList.add('printing'); });
    window.addEventListener('afterprint',  function() { document.body.classList.remove('printing'); });
});
