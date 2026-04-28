/* stock-history.js — Logika halaman riwayat stok */
$(document).ready(function() {
    if ($.fn.DataTable && $('#historyTable').length) {
        $('#historyTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            order: [
                [0, 'desc']
            ]
        });
    }
});

function printHistory() {
    const rows = document.querySelectorAll('#historyTable tbody tr');
    let tableRows = '';
    let totalIn = 0, totalOut = 0;
    rows.forEach((tr, idx) => {
        const cells = tr.querySelectorAll('td');
        if (!cells.length) return;
        const tanggal    = cells[0]?.innerText.trim() ?? '-';
        const namaBarang = cells[1]?.innerText.trim().split('\n')[0] ?? '-';
        const tipe       = cells[2]?.innerText.trim() ?? '-';
        const jumlahTxt  = cells[3]?.innerText.trim().replace(/[^0-9]/g,'') ?? '0';
        const jumlah     = parseInt(jumlahTxt) || 0;
        const stokSisa   = cells[4]?.innerText.trim().split('\n')[0] ?? '-';
        const ket        = cells[5]?.innerText.trim() ?? '-';
        if (tipe.toUpperCase().includes('MASUK') || tipe.toUpperCase() === 'IN') totalIn  += jumlah;
        if (tipe.toUpperCase().includes('KELUAR') || tipe.toUpperCase() === 'OUT') totalOut += jumlah;
        tableRows += `
            <tr>
                <td style="text-align:center">${idx+1}</td>
                <td>${tanggal}</td>
                <td>${namaBarang}</td>
                <td style="text-align:center">${tipe}</td>
                <td style="text-align:center">${jumlah.toLocaleString('id-ID')}</td>
                <td style="text-align:center">${stokSisa}</td>
                <td>${ket}</td>
            </tr>`;
    });
    tableRows += `
        <tr style="font-weight:bold;border-top:2px solid #000">
            <td colspan="3">TOTAL</td>
            <td></td>
            <td style="text-align:center">${totalIn.toLocaleString('id-ID')} IN / ${totalOut.toLocaleString('id-ID')} OUT</td>
            <td colspan="2"></td>
        </tr>`;

    const tanggalCetak = new Date().toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'});
    const html = `<!DOCTYPE html><html><head><meta charset="UTF-8">
    <title>Riwayat Mutasi Stok</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 9pt; margin: 20px; }
        .header-row { margin-bottom: 2px; }
        .header-row span.label { display:inline-block; width:80px; }
        .title  { text-align:center; font-weight:bold; font-size:11pt; margin:10px 0 4px; }
        .subtitle { text-align:center; font-size:9pt; margin-bottom:12px; }
        table { width:100%; border-collapse:collapse; }
        th { background:#f2f2f2; border:1px solid #000; padding:5px 4px; text-align:center; font-weight:bold; }
        td { border:1px solid #000; padding:3px 4px; }
        .sig  { margin-top:28px; }
        .sig table { border:none; } .sig td { border:none; padding:2px; }
    </style></head><body>
    <div class="header-row"><strong>LAPORAN RIWAYAT MUTASI STOK PERSEDIAAN</strong></div>
    <div class="header-row"><span class="label">Nomor</span>: ........../UN64.7/LK/${new Date().getFullYear()}</div>
    <div class="header-row"><span class="label">Tanggal</span>: ${tanggalCetak}</div>
    <div class="header-row"><span class="label">Unit</span>: Fakultas Ilmu Komputer</div>
    <div class="title">LAPORAN RIWAYAT MUTASI BARANG PERSEDIAAN</div>
    <div class="subtitle">TANGGAL CETAK: ${tanggalCetak.toUpperCase()}</div>
    <table><thead>
        <tr>
            <th width="4%">No</th>
            <th width="14%">Tgl & Waktu</th>
            <th width="27%">Nama Barang</th>
            <th width="8%">Tipe</th>
            <th width="8%">Jumlah</th>
            <th width="10%">Stok Sisa</th>
            <th>Referensi / Ket</th>
        </tr>
    </thead><tbody>${tableRows}</tbody></table>
    <div class="sig"><table width="100%">
        <tr><td width="50%">Mengetahui,</td><td></td></tr>
        <tr><td>A.n Dekan</td><td>Operator Persediaan</td></tr>
        <tr><td>Wakil Dekan Bidang Umum dan Keuangan</td><td></td></tr>
        <tr><td><br><br><br><br></td><td></td></tr>
        <tr><td><u>Betha Nurina Sari, M.Kom.</u></td><td><u>M Rizki Fauzi S, S.Pd.</u></td></tr>
        <tr><td>NIP. 198910232018032001</td><td></td></tr>
    </table></div>
    <script>window.onload=function(){window.print();}<\/script>
    </body></html>`;

    const w = window.open('', '_blank', 'width=900,height=700');
    w.document.write(html);
    w.document.close();
}
