/* reports-movements.js — Logic halaman Laporan Pergerakan Stok */
'use strict';

function printMovements() {
    var rows = document.querySelectorAll('table tbody tr');
    var tableRows = '', totalIn = 0, totalOut = 0;

    rows.forEach(function (tr, idx) {
        var cells = tr.querySelectorAll('td');
        if (!cells.length || cells.length < 6) return;
        var tanggal    = (cells[0] && cells[0].innerText.trim()) || '-';
        var refNo      = (cells[1] && cells[1].innerText.trim()) || '-';
        var tipe       = (cells[2] && cells[2].innerText.trim()) || '-';
        var namaBarang = cells[3] ? cells[3].innerText.trim().split('\n')[0] : '-';
        var kategori   = (cells[4] && cells[4].innerText.trim()) || '-';
        var jumlah     = parseInt(((cells[5] && cells[5].innerText.trim()) || '0').replace(/[^0-9]/g, '')) || 0;
        var catatan    = (cells[6] && cells[6].innerText.trim()) || '-';

        if (tipe.toLowerCase().includes('masuk') || tipe === 'IN') totalIn  += jumlah;
        if (tipe.toLowerCase().includes('keluar') || tipe === 'OUT') totalOut += jumlah;

        tableRows +=
            '<tr>' +
            '<td style="text-align:center">' + (idx + 1) + '</td>' +
            '<td style="text-align:center">' + tanggal + '</td>' +
            '<td>' + namaBarang + '</td>' +
            '<td style="text-align:center">' + refNo + '</td>' +
            '<td>' + kategori + '</td>' +
            '<td style="text-align:center">' + tipe + '</td>' +
            '<td style="text-align:center">' + jumlah.toLocaleString('id-ID') + '</td>' +
            '<td>' + catatan + '</td></tr>';
    });

    tableRows +=
        '<tr style="font-weight:bold;border-top:2px solid #000">' +
        '<td colspan="5">TOTAL</td>' +
        '<td style="text-align:center">IN: ' + totalIn.toLocaleString('id-ID') + '</td>' +
        '<td style="text-align:center">' + totalOut.toLocaleString('id-ID') + '</td>' +
        '<td></td></tr>';

    var tgl = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
    var css = 'body{font-family:Arial,sans-serif;font-size:9pt;margin:20px}' +
        '.hrow{margin-bottom:2px}.hrow span.lbl{display:inline-block;width:80px}' +
        '.ttl{text-align:center;font-weight:bold;font-size:11pt;margin:10px 0 4px}' +
        '.sub{text-align:center;font-size:9pt;margin-bottom:12px}' +
        'table{width:100%;border-collapse:collapse}' +
        'th{background:#f2f2f2;border:1px solid #000;padding:5px 4px;text-align:center;font-weight:bold}' +
        'td{border:1px solid #000;padding:3px 4px}' +
        '.sig{margin-top:28px}.sig table{border:none}.sig td{border:none;padding:2px}' +
        '@media print{button{display:none}}';

    var html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Laporan Mutasi Stok</title>' +
        '<style>' + css + '</style></head><body>' +
        '<div class="hrow"><strong>LAPORAN MUTASI / PERGERAKAN STOK PERSEDIAAN</strong></div>' +
        '<div class="hrow"><span class="lbl">Nomor</span>: ........../UN64.7/LK/' + new Date().getFullYear() + '</div>' +
        '<div class="hrow"><span class="lbl">Tanggal</span>: ' + tgl + '</div>' +
        '<div class="hrow"><span class="lbl">Unit</span>: Fakultas Ilmu Komputer</div>' +
        '<div class="ttl">LAPORAN MUTASI BARANG PERSEDIAAN</div>' +
        '<div class="sub">TANGGAL CETAK: ' + tgl.toUpperCase() + '</div>' +
        '<table><thead><tr>' +
        '<th width="4%">No</th><th width="13%">Tanggal</th><th width="25%">Nama Barang</th>' +
        '<th width="10%">No. Ref</th><th width="12%">Kategori</th>' +
        '<th width="7%">Tipe</th><th width="7%">Jumlah</th><th>Keterangan</th>' +
        '</tr></thead><tbody>' + tableRows + '</tbody></table>' +
        '<div class="sig"><table width="100%">' +
        '<tr><td width="60%">Mengetahui,</td><td></td></tr>' +
        '<tr><td>A.n Dekan</td><td>Operator Persediaan</td></tr>' +
        '<tr><td>Wakil Dekan Bidang Umum dan Keuangan</td><td></td></tr>' +
        '<tr><td><br><br><br><br></td><td></td></tr>' +
        '<tr><td><u>Betha Nurina Sari, M.Kom.</u></td><td><u>M Rizki Fauzi S, S.Pd.</u></td></tr>' +
        '<tr><td>NIP. 198910232018032001</td><td></td></tr>' +
        '</table></div><script>window.onload=function(){window.print();}<\/script></body></html>';

    var w = window.open('', '_blank', 'width=900,height=700');
    w.document.write(html);
    w.document.close();
}
