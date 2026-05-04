<?php

namespace App\Controllers\Permintaan;

use App\Controllers\BaseController;
use App\Models\Permintaan\PermintaanModel;
use App\Models\Permintaan\ItemPermintaanModel;
use App\Models\MasterData\BarangModel;
use App\Models\Notifikasi\NotifikasiModel;
use App\Services\PermintaanService;
use Exception;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * PermintaanController - Controller untuk mengelola permintaan ATK
 *
 * Relasi:
 * - Permintaan terkait Barang dan Pengguna
 */
class PermintaanController extends BaseController
{
    protected PermintaanModel $modelPermintaan;
    protected ItemPermintaanModel $modelItemPermintaan;
    protected BarangModel $modelBarang;
    protected NotifikasiModel $modelNotifikasi;
    protected PermintaanService $permintaanService;

    public function __construct()
    {
        $this->modelPermintaan       = new PermintaanModel();
        $this->modelItemPermintaan   = new ItemPermintaanModel();
        $this->modelBarang           = new BarangModel();
        $this->modelNotifikasi       = new NotifikasiModel();

        // Ambil service utama agar logika bisnis permintaan terpusat di service.
        $this->permintaanService     = service('permintaan');
    }

    /**
     * Tambahkan kode resi ke URL sebagai query parameter
     */
    private function tambahkanResiKeUrl(string $url, string $kodeResi): string
    {
        $fragment = '';
        if (str_contains($url, '#')) {
            // Simpan fragment (#...) agar tidak hilang setelah query resi ditambahkan.
            [$url, $frag] = explode('#', $url, 2);
            $fragment = '#' . $frag;
        }

        // Gunakan separator sesuai kondisi URL: ? jika belum ada query, & jika sudah ada.
        $separator = str_contains($url, '?') ? '&' : '?';
        return $url . $separator . 'resi=' . rawurlencode($kodeResi) . $fragment;
    }

    private function getPermintaanRules(): array
    {
        return [
            'borrower_name' => 'required|min_length[3]|max_length[150]',
            'borrower_unit' => 'required',
            'email'         => 'required|valid_email',
            'product_id'    => 'required',
            'quantity'      => 'required',
            'request_date'  => 'required|valid_date',
        ];
    }

    /**
     * Handles the logic for creating a new request from either admin or public form.
     */
    private function prosesSimpanPermintaan()
    {
        $rules = $this->getPermintaanRules();

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $postData = $this->request->getPost();
            [$requestId, $kodeResi] = $this->permintaanService->buatPermintaan($postData);

            // Tentukan tujuan akhir setelah simpan: form publik atau halaman internal.
            $redirectUrl = $this->request->getPost('_redirect');
            $isPublicForm = str_contains((string)$redirectUrl, 'ask');

            if ($isPublicForm) {
                return redirect()->to('/ask/success')
                    ->with('request_id', $requestId)
                    ->with('borrower_name', $this->request->getPost('borrower_name'))
                    ->with('kode_resi', $kodeResi);
            }

            $redirectUrl = $redirectUrl ?: '/requests';
            $redirectUrlDenganResi = $this->tambahkanResiKeUrl($redirectUrl, $kodeResi);

            return redirect()->to($redirectUrlDenganResi)
                ->with('success', 'Permintaan berhasil diajukan.')
                ->with('kode_resi', $kodeResi);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    private function findByReference(string $referenceNo): ?array
    {
        // Support 2 format referensi: "REQ-123" atau kode resi timestamp.
        if (preg_match('/^REQ-(\d+)$/i', $referenceNo, $matches)) {
            return $this->modelPermintaan->find((int) $matches[1]);
        }

        return $this->modelPermintaan->where('receipt_code', $referenceNo)->first();
    }

    private function getItemDetail(int $requestId): array
    {
        $items = $this->modelItemPermintaan->where('request_id', $requestId)->findAll();
        $itemEnriched = [];

        foreach ($items as $item) {
            $itemEnriched[] = [
                'item' => $item,
                'barang' => $this->modelBarang->find($item['product_id']),
            ];
        }

        return $itemEnriched;
    }

    private function getStatusBadges(): array
    {
        return [
            'requested'   => ['text' => 'Menunggu Persetujuan', 'color' => 'warning', 'icon' => 'hourglass-split'],
            'approved'    => ['text' => 'Disetujui', 'color' => 'info', 'icon' => 'check-circle'],
            'distributed' => ['text' => 'Sudah Dikirim', 'color' => 'success', 'icon' => 'check2-all'],
            'cancelled'   => ['text' => 'Dibatalkan', 'color' => 'danger', 'icon' => 'x-circle'],
        ];
    }

    private function getReferenceDisplay(array $dataPermintaan, int $requestId): string
    {
        $referenceNoDisplay = (string) ($dataPermintaan['receipt_code'] ?? '');
        if ($referenceNoDisplay === '') {
            $referenceNoDisplay = 'REQ-' . str_pad((string) $requestId, 4, '0', STR_PAD_LEFT);
        }

        return $referenceNoDisplay;
    }

    /**
     * Tampilkan daftar permintaan
     */
    public function index()
    {
        $this->setPageData('Daftar Permintaan', 'Manajemen permintaan dan distribusi ATK');
        // Backfill otomatis untuk data lama yang belum memiliki kode resi.
        $this->permintaanService->isiKodeResiKosong();

        $status = $this->request->getGet('status');
        $filterResi = trim((string) $this->request->getGet('resi'));
        $filterPeriod = $this->request->getGet('period');
        $builder = $this->modelPermintaan->orderBy('created_at', 'DESC');

        if ($status) {
            $builder->where('status', $status);
        }

        if ($filterPeriod) {
            $builder->like('request_date', $filterPeriod, 'after');
        }

        if ($filterResi !== '') {
            // Pencarian fleksibel: bisa berdasarkan receipt_code atau ID numerik.
            $builder->groupStart()
                ->like('receipt_code', $filterResi)
                ->orLike('id', preg_replace('/\D+/', '', $filterResi))
                ->groupEnd();
        }

        $requests = $builder->findAll();

        $data = [
            'daftarPinjaman' => $requests,
            'filterStatus'   => $status,
            'filterResi'     => $filterResi,
            'filterPeriod'   => $filterPeriod,
        ];

        return $this->render('permintaan/index', $data);
    }

    /**
     * Form tambah/buat permintaan baru
     */
    public function tambah()
    {
        $this->setPageData('Buat Permintaan', 'Formulir permintaan ATK baru');

        $products = $this->modelBarang->where('is_active', true)->orderBy('name', 'ASC')->findAll();

        $data = [
            'daftarBarang' => $products
        ];

        return $this->render('permintaan/create', $data);
    }

    /**
     * Simpan permintaan baru (dari admin)
     */
    public function simpan()
    {
        $rules = $this->getPermintaanRules();

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $postData = $this->request->getPost();
        $result = $this->permintaanService->buatPermintaan($postData);

        if ($result['success']) {
            $requestId = $result['data']['request_id'];
            $kodeResi = $result['data']['receipt_code'];

            $redirectUrl = $this->request->getPost('_redirect') ?: '/requests';
            $redirectUrlDenganResi = $this->tambahkanResiKeUrl($redirectUrl, $kodeResi);

            return redirect()->to($redirectUrlDenganResi)
                ->with('success', 'Permintaan berhasil diajukan.')
                ->with('kode_resi', $kodeResi);
        }

        return redirect()->back()->withInput()->with('error', $result['message']);
    }

    /**
     * Tampilkan detail permintaan
     */
    public function detail($id)
    {
        $dataPermintaan = $this->modelPermintaan->getPermintaanDenganItem((int)$id);

        if (!$dataPermintaan) {
            return redirect()->to('/requests')->with('error', 'Data tidak ditemukan.');
        }

        $this->setPageData('Detail Permintaan', 'Review detail permintaan ATK');

        return $this->render('permintaan/show', ['pinjaman' => $dataPermintaan]);
    }

    /**
     * Setujui permintaan (AJAX)
     */
    public function setujui($id)
    {
        $result = $this->permintaanService->setujuiPermintaan((int)$id);

        return $this->jsonResponse($result, $result['success'] ? 200 : 404);
    }

    /**
     * Distribusikan permintaan barang dan kurangi stok (AJAX)
     */
    public function distribusikan($id)
    {
        try {
            $userId = session()->get('userId');
            $userName = session()->get('name') ?: 'Admin';

            if (!$userId || !is_numeric($userId)) {
                return $this->jsonResponse(['success' => false, 'message' => 'Session tidak valid. Silakan login ulang.'], 401);
            }

            $result = $this->permintaanService->distribusikanPermintaan((int)$id, (int)$userId, $userName);

            return $this->jsonResponse($result, $result['success'] ? 200 : 400);
        } catch (Exception $e) {
            log_message('error', 'Error saat distribusi: ' . $e->getMessage());
            return $this->jsonResponse(['success' => false, 'message' => 'Gagal distribusi: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Batalkan permintaan (AJAX)
     */
    public function batalkan($id)
    {
        $reason = $this->request->getPost('reason');
        $result = $this->permintaanService->batalkanPermintaan((int)$id, $reason);

        return $this->jsonResponse($result, $result['success'] ? 200 : 400);
    }

    /**
     * Halaman publik - form permintaan barang (tanpa login)
     */
    public function askForm()
    {
        $barang = $this->modelBarang
            ->where('is_active', true)
            ->orderBy('name', 'ASC')
            ->findAll();

        $data = [
            'title'       => 'Ajukan Permintaan ATK | SIMATK',
            'daftarBarang' => $barang,
        ];

        return view('permintaan/ask', $data);
    }

    /**
     * Proses simpan permintaan publik
     */
    public function askStore()
    {
        $rules = $this->getPermintaanRules();

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $postData = $this->request->getPost();
        $result = $this->permintaanService->buatPermintaan($postData);

        if ($result['success']) {
            $requestId = $result['data']['request_id'];
            $kodeResi = $result['data']['receipt_code'];

            return redirect()->to('/')
                ->with('_open_success_modal', true)
                ->with('request_id', $requestId)
                ->with('borrower_name', $this->request->getPost('borrower_name'))
                ->with('kode_resi', $kodeResi);
        }

        return redirect()->back()->withInput()->with('error', $result['message']);
    }

    /**
     * Halaman sukses setelah permintaan publik dikirim
     */
    public function askSuccess()
    {
        $data = [
            'title'         => 'Permintaan Terkirim | SIMATK',
            'request_id'    => session()->getFlashdata('request_id'),
            'borrower_name' => session()->getFlashdata('borrower_name'),
            'kode_resi'     => session()->getFlashdata('kode_resi'),
        ];

        return view('permintaan/ask_success', $data);
    }

    /**
     * Halaman publik - form tracking permintaan (tanpa login)
     */
    public function trackForm()
    {
        $data = [
            'title' => 'Lacak Permintaan ATK | SIMATK',
        ];

        return view('permintaan/track', $data);
    }

    /**
     * Proses pencarian/lacak status permintaan publik
     */
    public function lacakStatus()
    {
        $referenceNo = trim((string) ($this->request->getPost('reference_no') ?? ''));
        $emailRaw = trim((string) ($this->request->getPost('email') ?? ''));
        $email = strtolower($emailRaw);
        // Jika request datang dari modal beranda, respons diarahkan untuk membuka modal hasil.
        $fromHomeModal = (string) ($this->request->getPost('_from') ?? '') === 'home-modal';

        $kirimError = static function (string $pesan, bool $bukaModal = false) {
            $response = redirect()->back()->withInput()->with('error', $pesan);
            if ($bukaModal) {
                $response = $response->with('_open_track_modal', true);
            }

            return $response;
        };

        // Validasi input
        if ($referenceNo === '') {
            return $kirimError('Nomor referensi harus diisi.', $fromHomeModal);
        }

        if ($emailRaw !== '' && !filter_var($emailRaw, FILTER_VALIDATE_EMAIL)) {
            return $kirimError('Format email tidak valid.', $fromHomeModal);
        }

        $dataPermintaan = $this->findByReference($referenceNo);

        if (!$dataPermintaan) {
            return $kirimError('Permintaan tidak ditemukan.', $fromHomeModal);
        }

        // Email bersifat opsional. Jika diisi, nilainya harus sama dengan email pada permintaan.
        if ($email !== '' && strtolower((string) ($dataPermintaan['email'] ?? '')) !== $email) {
            return $kirimError('Email tidak sesuai dengan data permintaan.', $fromHomeModal);
        }

        $requestId = (int) ($dataPermintaan['id'] ?? 0);
        if ($requestId <= 0) {
            return $kirimError('Data permintaan tidak valid.', $fromHomeModal);
        }

        $referenceNoDisplay = $this->getReferenceDisplay($dataPermintaan, $requestId);
        $itemEnriched = $this->getItemDetail($requestId);
        $statusBadges = $this->getStatusBadges();
        $statusSaatIni = (string) ($dataPermintaan['status'] ?? 'requested');
        $statusMeta = $statusBadges[$statusSaatIni] ?? ['text' => 'Tidak Diketahui', 'color' => 'secondary', 'icon' => 'question-circle'];

        if ($fromHomeModal) {
            return redirect()->to('/')
                ->with('_open_track_result_modal', true)
                ->with('track_result_data', [
                    'reference_no'   => $referenceNoDisplay,
                    'request_no'     => 'REQ-' . str_pad((string) $requestId, 4, '0', STR_PAD_LEFT),
                    'borrower_name'  => (string) ($dataPermintaan['borrower_name'] ?? '-'),
                    'borrower_unit'  => (string) ($dataPermintaan['borrower_unit'] ?? '-'),
                    'request_date'   => (string) ($dataPermintaan['request_date'] ?? ''),
                    'status_text'    => (string) ($statusMeta['text'] ?? 'Tidak Diketahui'),
                    'status_color'   => (string) ($statusMeta['color'] ?? 'secondary'),
                    'status_icon'    => (string) ($statusMeta['icon'] ?? 'question-circle'),
                    'status_reason'  => (string) ($dataPermintaan['status_reason'] ?? ''),
                ]);
        }

        $data = [
            'title'              => 'Lacak Permintaan ATK | SIMATK',
            'referenceNo'        => $referenceNoDisplay,
            'permintaan'         => $dataPermintaan,
            'itemPermintaan'     => $itemEnriched,
            'statusBadges'       => $statusBadges,
        ];

        return view('permintaan/track_result', $data);
    }

    private function getExportData(): array
    {
        $status = $this->request->getGet('status');
        $filterResi = trim((string) $this->request->getGet('resi'));
        $filterPeriod = $this->request->getGet('period');
        
        $builder = $this->modelPermintaan->orderBy('created_at', 'DESC');

        if ($status) {
            $builder->where('status', $status);
        }

        if ($filterPeriod) {
            $builder->like('request_date', $filterPeriod, 'after');
        }

        if ($filterResi !== '') {
            $builder->groupStart()
                ->like('receipt_code', $filterResi)
                ->orLike('id', preg_replace('/\D+/', '', $filterResi))
                ->groupEnd();
        }

        $requests = $builder->findAll();

        $exportData = [];
        foreach ($requests as $req) {
            $items = $this->modelItemPermintaan->select('request_items.*, barang.name as product_name, barang.price as product_price')
                ->join('barang', 'barang.id = request_items.product_id', 'left')
                ->where('request_items.request_id', $req['id'])
                ->findAll();
                
            foreach ($items as $item) {
                $hargaSatuan = (float)($item['product_price'] ?? 0);
                $jumlah = (int)$item['quantity'];
                
                $exportData[] = [
                    'id' => $req['id'],
                    'receipt_code' => $req['receipt_code'],
                    'borrower_name' => $req['borrower_name'],
                    'borrower_unit' => $req['borrower_unit'],
                    'request_date' => $req['request_date'],
                    'status' => $req['status'],
                    'product_name' => $item['product_name'] ?? 'Unknown',
                    'quantity' => $jumlah,
                    'price' => $hargaSatuan,
                    'total_price' => $jumlah * $hargaSatuan,
                ];
            }
        }

        return $exportData;
    }

    public function exportExcel()
    {
        $data = $this->getExportData();
        $filterPeriod = $this->request->getGet('period');
        $tanggalBulan = $filterPeriod ? date('F Y', strtotime($filterPeriod)) : date('F Y');
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'LAMPIRAN BERITA ACARA BARANG KELUAR FISIK PERSEDIAAN');
        $sheet->setCellValue('A2', 'Nomor: ........../UN64.7/LK/' . date('Y'));
        $sheet->setCellValue('A3', 'Tanggal: ' . date('d F Y'));
        $sheet->setCellValue('A4', 'Unit: Fakultas Ilmu Komputer');

        $sheet->setCellValue('A6', 'LAPORAN BARANG KELUAR');
        $sheet->setCellValue('A7', 'UNTUK PERIODE YANG BERAKHIR TANGGAL ' . strtoupper($tanggalBulan));
        $sheet->setCellValue('A8', 'TAHUN ANGGARAN ' . date('Y', strtotime($filterPeriod ?: date('Y-m'))));

        $sheet->setCellValue('A10', 'No')
            ->setCellValue('B10', 'Nama Peminjam')
            ->setCellValue('C10', 'Unit Peminjam')
            ->setCellValue('D10', 'Tanggal Peminjam')
            ->setCellValue('E10', 'Jenis Barang')
            ->setCellValue('F10', 'Jumlah')
            ->setCellValue('G10', 'Harga Satuan')
            ->setCellValue('H10', 'Total Harga');

        $rowNum = 11;
        $idx = 1;
        $totalQty = 0;
        $totalHarga = 0;

        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNum, $idx)
                ->setCellValue('B' . $rowNum, $row['borrower_name'])
                ->setCellValue('C' . $rowNum, $row['borrower_unit'])
                ->setCellValue('D' . $rowNum, date('d/m/Y', strtotime($row['request_date'])))
                ->setCellValue('E' . $rowNum, $row['product_name'])
                ->setCellValue('F' . $rowNum, $row['quantity'])
                ->setCellValue('G' . $rowNum, $row['price'])
                ->setCellValue('H' . $rowNum, $row['total_price']);
            
            $totalQty += $row['quantity'];
            $totalHarga += $row['total_price'];

            $idx++;
            $rowNum++;
        }

        $sheet->setCellValue('A' . $rowNum, 'TOTAL');
        $sheet->mergeCells("A{$rowNum}:E{$rowNum}");
        $sheet->setCellValue('F' . $rowNum, $totalQty);
        $sheet->setCellValue('H' . $rowNum, $totalHarga);

        $fileName = 'Laporan_Barang_Keluar_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function exportPdf()
    {
        $data = $this->getExportData();
        $filterPeriod = $this->request->getGet('period');
        
        $months = [
            1 => 'JANUARI', 2 => 'FEBRUARI', 3 => 'MARET', 4 => 'APRIL', 5 => 'MEI', 6 => 'JUNI',
            7 => 'JULI', 8 => 'AGUSTUS', 9 => 'SEPTEMBER', 10 => 'OKTOBER', 11 => 'NOVEMBER', 12 => 'DESEMBER'
        ];
        
        if ($filterPeriod) {
            $time = strtotime($filterPeriod);
            $month = $months[(int)date('n', $time)];
            $year = date('Y', $time);
        } else {
            $month = $months[(int)date('n')];
            $year = date('Y');
        }
        
        $periodeUpper = $month . ' ' . $year;
        $tanggal = date('d ') . ucfirst(strtolower($month)) . ' ' . date('Y');

        $html = '<!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: "Helvetica", "Arial", sans-serif; font-size: 10pt; line-height: 1.3; }
                .header { margin-bottom: 20px; font-size: 9pt; }
                .header-row { margin-bottom: 2px; }
                .header-row span.label { display: inline-block; width: 80px; }
                .title { text-align: center; font-weight: bold; font-size: 11pt; margin: 10px 0 5px; }
                .subtitle { text-align: center; font-size: 9pt; margin-bottom: 15px; }
                table { width: 100%; border-collapse: collapse; }
                th { background-color: #f2f2f2; border: 1px solid #000; padding: 6px 4px; text-align: center; font-size: 8pt; font-weight: bold; }
                td { border: 1px solid #000; padding: 4px; font-size: 8pt; }
                td.center { text-align: center; }
                td.right { text-align: right; }
                tr.total td { font-weight: bold; border-top: 2px solid #000; }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="header-row"><strong>LAMPIRAN BERITA ACARA BARANG KELUAR FISIK PERSEDIAAN</strong></div>
                <div class="header-row"><span class="label">Nomor</span>: ........../UN64.7/LK/' . $year . '</div>
                <div class="header-row"><span class="label">Tanggal</span>: ' . $tanggal . '</div>
                <div class="header-row"><span class="label">Unit</span>: Fakultas Ilmu Komputer</div>
            </div>

            <div class="title">LAPORAN BARANG KELUAR</div>
            <div class="subtitle">UNTUK PERIODE YANG BERAKHIR TANGGAL ' . $periodeUpper . '</div>
            <div class="subtitle">TAHUN ANGGARAN ' . $year . '</div>

            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="18%">Nama Peminjam</th>
                        <th width="12%">Tgl Peminjam</th>
                        <th width="25%">Jenis Barang</th>
                        <th width="8%">Jumlah</th>
                        <th width="15%">Harga Satuan</th>
                        <th width="15%">Total Harga</th>
                    </tr>
                </thead>
                <tbody>';

        $totalQty = 0;
        $totalHarga = 0;
        $idx = 1;

        foreach ($data as $row) {
            $qty = $row['quantity'];
            $price = $row['price'];
            $total = $row['total_price'];
            $date = date('d/m/Y', strtotime($row['request_date']));

            $html .= "<tr>
                        <td class='center'>{$idx}</td>
                        <td>{$row['borrower_name']} ({$row['borrower_unit']})</td>
                        <td class='center'>{$date}</td>
                        <td>{$row['product_name']}</td>
                        <td class='center'>{$qty}</td>
                        <td class='right'>" . number_format($price, 0, ',', '.') . "</td>
                        <td class='right'>" . number_format($total, 0, ',', '.') . "</td>
                      </tr>";
            
            $totalQty += $qty;
            $totalHarga += $total;
            $idx++;
        }
        
        $html .= "<tr class='total'>
                    <td colspan='4' class='center'>TOTAL</td>
                    <td class='center'>{$totalQty}</td>
                    <td></td>
                    <td class='right'>" . number_format($totalHarga, 0, ',', '.') . "</td>
                  </tr>";

        $html .= '</tbody></table></body></html>';

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('Laporan_Barang_Keluar_' . date('Ymd_His') . '.pdf', ["Attachment" => true]);
        exit;
    }

    public function exportPrint()
    {
        $data = $this->getExportData();
        $filterPeriod = $this->request->getGet('period');
        
        $months = [
            1 => 'JANUARI', 2 => 'FEBRUARI', 3 => 'MARET', 4 => 'APRIL', 5 => 'MEI', 6 => 'JUNI',
            7 => 'JULI', 8 => 'AGUSTUS', 9 => 'SEPTEMBER', 10 => 'OKTOBER', 11 => 'NOVEMBER', 12 => 'DESEMBER'
        ];
        
        if ($filterPeriod) {
            $time = strtotime($filterPeriod);
            $month = $months[(int)date('n', $time)];
            $year = date('Y', $time);
        } else {
            $month = $months[(int)date('n')];
            $year = date('Y');
        }
        
        $periodeUpper = $month . ' ' . $year;
        $tanggal = date('d ') . ucfirst(strtolower($month)) . ' ' . date('Y');

        $html = '<!DOCTYPE html>
        <html>
        <head>
            <title>Cetak Laporan Barang Keluar</title>
            <style>
                body { font-family: "Helvetica", "Arial", sans-serif; font-size: 10pt; line-height: 1.3; }
                .header { margin-bottom: 20px; font-size: 9pt; }
                .header-row { margin-bottom: 2px; }
                .header-row span.label { display: inline-block; width: 80px; }
                .title { text-align: center; font-weight: bold; font-size: 11pt; margin: 10px 0 5px; }
                .subtitle { text-align: center; font-size: 9pt; margin-bottom: 15px; }
                table { width: 100%; border-collapse: collapse; }
                th { background-color: #f2f2f2; border: 1px solid #000; padding: 6px 4px; text-align: center; font-size: 8pt; font-weight: bold; }
                td { border: 1px solid #000; padding: 4px; font-size: 8pt; }
                td.center { text-align: center; }
                td.right { text-align: right; }
                tr.total td { font-weight: bold; border-top: 2px solid #000; }
                @media print {
                    @page { margin: 15mm; }
                    body { -webkit-print-color-adjust: exact; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="header-row"><strong>LAMPIRAN BERITA ACARA BARANG KELUAR FISIK PERSEDIAAN</strong></div>
                <div class="header-row"><span class="label">Nomor</span>: ........../UN64.7/LK/' . $year . '</div>
                <div class="header-row"><span class="label">Tanggal</span>: ' . $tanggal . '</div>
                <div class="header-row"><span class="label">Unit</span>: Fakultas Ilmu Komputer</div>
            </div>

            <div class="title">LAPORAN BARANG KELUAR</div>
            <div class="subtitle">UNTUK PERIODE YANG BERAKHIR TANGGAL ' . $periodeUpper . '</div>
            <div class="subtitle">TAHUN ANGGARAN ' . $year . '</div>

            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="18%">Nama Peminjam</th>
                        <th width="12%">Tgl Peminjam</th>
                        <th width="25%">Jenis Barang</th>
                        <th width="8%">Jumlah</th>
                        <th width="15%">Harga Satuan</th>
                        <th width="15%">Total Harga</th>
                    </tr>
                </thead>
                <tbody>';

        $totalQty = 0;
        $totalHarga = 0;
        $idx = 1;

        foreach ($data as $row) {
            $qty = $row['quantity'];
            $price = $row['price'];
            $total = $row['total_price'];
            $date = date('d/m/Y', strtotime($row['request_date']));

            $html .= "<tr>
                        <td class='center'>{$idx}</td>
                        <td>{$row['borrower_name']} ({$row['borrower_unit']})</td>
                        <td class='center'>{$date}</td>
                        <td>{$row['product_name']}</td>
                        <td class='center'>{$qty}</td>
                        <td class='right'>" . number_format($price, 0, ',', '.') . "</td>
                        <td class='right'>" . number_format($total, 0, ',', '.') . "</td>
                      </tr>";
            
            $totalQty += $qty;
            $totalHarga += $total;
            $idx++;
        }
        
        $html .= "<tr class='total'>
                    <td colspan='4' class='center'>TOTAL</td>
                    <td class='center'>{$totalQty}</td>
                    <td></td>
                    <td class='right'>" . number_format($totalHarga, 0, ',', '.') . "</td>
                  </tr>";

        $html .= '</tbody></table>
            <script>
                window.onload = function() {
                    window.print();
                }
            </script>
        </body></html>';

        return $this->response->setBody($html);
    }
}
