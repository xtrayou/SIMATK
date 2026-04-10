<?php

namespace App\Controllers;

use App\Models\PermintaanModel;
use App\Models\ItemPermintaanModel;
use App\Models\ProdukModel;
use App\Models\MutasiStokModel;
use App\Models\NotifikasiModel;
use Exception;

/**
 * PermintaanController - Controller untuk mengelola permintaan ATK
 *
 * Relasi:
 * - Permintaan terkait Produk dan Pengguna
 */
class PermintaanController extends BaseController
{
    protected PermintaanModel $modelPermintaan;
    protected ItemPermintaanModel $modelItemPermintaan;
    protected ProdukModel $modelProduk;
    protected MutasiStokModel $modelMutasiStok;
    protected NotifikasiModel $modelNotifikasi;

    public function __construct()
    {
        $this->modelPermintaan       = new PermintaanModel();
        $this->modelItemPermintaan   = new ItemPermintaanModel();
        $this->modelProduk           = new ProdukModel();
        $this->modelMutasiStok       = new MutasiStokModel();
        $this->modelNotifikasi       = new NotifikasiModel();
    }

    /**
     * Tambahkan kode resi ke URL sebagai query parameter
     */
    private function tambahkanResiKeUrl(string $url, string $kodeResi): string
    {
        $fragment = '';
        if (str_contains($url, '#')) {
            [$url, $frag] = explode('#', $url, 2);
            $fragment = '#' . $frag;
        }

        $separator = str_contains($url, '?') ? '&' : '?';
        return $url . $separator . 'resi=' . rawurlencode($kodeResi) . $fragment;
    }

    /**
     * Generate kode resi unik berdasarkan timestamp
     */
    private function generateKodeResiUnik(): string
    {
        do {
            $kode = date('Ymd-His');
            $exists = $this->modelPermintaan->where('receipt_code', $kode)->countAllResults() > 0;
            if ($exists) {
                sleep(1);
            }
        } while ($exists);

        return $kode;
    }

    /**
     * Generate kode resi dari timestamp tertentu
     */
    private function generateKodeResiDariTimestamp(int $timestamp): string
    {
        return date('Ymd-His', $timestamp);
    }

    /**
     * Pastikan kode resi unik untuk baris tertentu
     */
    private function pastikanKodeResiUnik(string $kandidat, int $excludeId, int $baseTimestamp): string
    {
        $kode = $kandidat;
        $ts = $baseTimestamp;

        while (
            $this->modelPermintaan
            ->where('receipt_code', $kode)
            ->where('id !=', $excludeId)
            ->countAllResults() > 0
        ) {
            $ts++;
            $kode = $this->generateKodeResiDariTimestamp($ts);
        }

        return $kode;
    }

    /**
     * Isi ulang kode resi yang kosong pada data lama
     */
    private function isiKodeResiKosong(): void
    {
        $rows = $this->modelPermintaan
            ->select('id, created_at')
            ->groupStart()
            ->where('receipt_code IS NULL', null, false)
            ->orWhere('receipt_code', '')
            ->groupEnd()
            ->findAll();

        if (empty($rows)) {
            return;
        }

        foreach ($rows as $row) {
            $timestamp = !empty($row['created_at']) ? strtotime((string) $row['created_at']) : time();
            if ($timestamp === false) {
                $timestamp = time();
            }

            $kandidat = $this->generateKodeResiDariTimestamp($timestamp);
            $kodeUnik = $this->pastikanKodeResiUnik($kandidat, (int) $row['id'], $timestamp);

            $this->modelPermintaan->update((int) $row['id'], [
                'receipt_code' => $kodeUnik,
            ]);
        }
    }

    /**
     * Tampilkan daftar permintaan
     */
    public function index()
    {
        $this->setPageData('Daftar Permintaan', 'Manajemen permintaan dan distribusi ATK');
        $this->isiKodeResiKosong();

        $status = $this->request->getGet('status');
        $filterResi = trim((string) $this->request->getGet('resi'));
        $builder = $this->modelPermintaan->orderBy('created_at', 'DESC');

        if ($status) {
            $builder->where('status', $status);
        }

        if ($filterResi !== '') {
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
        ];

        return $this->render('permintaan/index', $data);
    }

    /**
     * Form tambah/buat permintaan baru
     */
    public function tambah()
    {
        $this->setPageData('Buat Permintaan', 'Formulir permintaan ATK baru');

        $products = $this->modelProduk->where('is_active', true)->orderBy('name', 'ASC')->findAll();

        $data = [
            'daftarProduk' => $products
        ];

        return $this->render('permintaan/create', $data);
    }

    /**
     * Simpan permintaan baru (dari admin)
     */
    public function simpan()
    {
        $rules = [
            'borrower_name' => 'required|min_length[3]|max_length[150]',
            'borrower_unit' => 'required',
            'email'         => 'required|valid_email',
            'product_id'    => 'required',
            'quantity'      => 'required',
            'request_date'  => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $kodeResi = $this->generateKodeResiUnik();
            $requestId = $this->modelPermintaan->insert([
                'borrower_name'       => $this->request->getPost('borrower_name'),
                'borrower_identifier' => $this->request->getPost('borrower_identifier'),
                'borrower_unit'       => $this->request->getPost('borrower_unit'),
                'email'               => $this->request->getPost('email'),
                'receipt_code'        => $kodeResi,
                'request_date'        => $this->request->getPost('request_date') ?: date('Y-m-d'),
                'status'              => 'requested',
                'notes'               => $this->request->getPost('notes'),
            ]);

            $productIds = (array) $this->request->getPost('product_id');
            $quantities = (array) $this->request->getPost('quantity');

            foreach ($productIds as $index => $pid) {
                if (empty($pid) || empty($quantities[$index])) continue;

                $this->modelItemPermintaan->insert([
                    'request_id' => $requestId,
                    'product_id' => $pid,
                    'quantity'   => $quantities[$index],
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new Exception('Gagal menyimpan data ke database.');
            }

            $redirectUrl = $this->request->getPost('_redirect') ?: '/requests';
            $redirectUrlDenganResi = $this->tambahkanResiKeUrl($redirectUrl, $kodeResi);

            // Kirim notifikasi permintaan baru
            try {
                $dataPermintaan = $this->modelPermintaan->find($requestId);
                if ($dataPermintaan) {
                    $this->modelNotifikasi->createNewRequestNotification($dataPermintaan);
                }
            } catch (\Throwable $e) {
                log_message('error', 'Gagal kirim notifikasi permintaan baru: ' . $e->getMessage());
            }

            return redirect()->to($redirectUrlDenganResi)
                ->with('success', 'Permintaan berhasil diajukan.')
                ->with('kode_resi', $kodeResi);
        } catch (Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
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
        $dataPermintaan = $this->modelPermintaan->find($id);
        if (!$dataPermintaan) return $this->jsonResponse(['status' => false, 'message' => 'Data tidak ditemukan'], 404);

        if ($this->modelPermintaan->update($id, ['status' => 'approved'])) {
            // Kirim notifikasi disetujui
            try {
                $this->modelNotifikasi->createRequestApprovedNotification($dataPermintaan);
            } catch (\Throwable $e) {
                log_message('error', 'Gagal kirim notifikasi approve: ' . $e->getMessage());
            }
            return $this->jsonResponse(['status' => true, 'message' => 'Permintaan disetujui.']);
        }

        return $this->jsonResponse(['status' => false, 'message' => 'Gagal memperbarui status.'], 500);
    }

    /**
     * Distribusikan barang dan kurangi stok (AJAX)
     */
    public function distribusikan($id)
    {
        $dataPermintaan = $this->modelPermintaan->find($id);
        if (!$dataPermintaan) {
            return $this->jsonResponse(['status' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        // Hindari distribusi ganda: jika mutasi REQ-{id} sudah ada, cukup sinkronkan status.
        $existingDistribution = $this->modelMutasiStok
            ->where('type', 'OUT')
            ->where('reference_no', 'REQ-' . $id)
            ->countAllResults();
        if ($existingDistribution > 0) {
            if (($dataPermintaan['status'] ?? '') !== 'distributed') {
                $this->modelPermintaan->update($id, ['status' => 'distributed']);
            }
            return $this->jsonResponse([
                'status' => true,
                'message' => 'Permintaan sudah pernah didistribusikan. Status disinkronkan ke didistribusikan.',
            ]);
        }

        if ($dataPermintaan['status'] !== 'approved') {
            return $this->jsonResponse(['status' => false, 'message' => 'Permintaan belum disetujui'], 400);
        }

        $daftarItem = $this->modelItemPermintaan->where('request_id', $id)->findAll();

        if (empty($daftarItem)) {
            return $this->jsonResponse(['status' => false, 'message' => 'Tidak ada item untuk didistribusikan'], 400);
        }

        // Cek stok sebelum distribusi
        $errorStok = [];
        foreach ($daftarItem as $item) {
            $produk = $this->modelProduk->find($item['product_id']);
            if (!$produk) {
                $errorStok[] = "Produk ID {$item['product_id']} tidak ditemukan";
                continue;
            }

            $stokSekarang = (int) ($produk['stock_baik'] ?? $produk['current_stock']);
            $jumlahDiminta = (int) $item['quantity'];

            if ($stokSekarang < $jumlahDiminta) {
                $errorStok[] = "{$produk['name']}: stok baik tersedia {$stokSekarang}, diminta {$jumlahDiminta}";
            }
        }

        if (!empty($errorStok)) {
            return $this->jsonResponse([
                'status' => false,
                'message' => 'Stok tidak mencukupi:<br>• ' . implode('<br>• ', $errorStok)
            ], 400);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $userId = session()->get('userId');
            if (!$userId || !is_numeric($userId)) {
                throw new Exception('Session tidak valid. Silakan login ulang.');
            }

            foreach ($daftarItem as $item) {
                $this->modelMutasiStok->buatMutasi([
                    'product_id'   => $item['product_id'],
                    'type'         => 'OUT',
                    'quantity'     => $item['quantity'],
                    'notes'        => 'Distribusi ATK - No. Permintaan: #' . $id . ' oleh ' . (session()->get('name') ?: 'Admin'),
                    'reference_no' => 'REQ-' . $id,
                    'created_by'   => (int) $userId
                ]);
            }

            $this->modelPermintaan->update($id, ['status' => 'distributed']);
            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new Exception('Gagal memproses mutasi stok. Silakan coba lagi.');
            }

            // Cek stok rendah/habis setelah distribusi
            try {
                foreach ($daftarItem as $item) {
                    $produk = $this->modelProduk->find($item['product_id']);
                    if (!$produk) continue;

                    $stokBaikSaatIni = (int) ($produk['stock_baik'] ?? $produk['current_stock']);
                    $produkUntukNotifikasi = $produk;
                    $produkUntukNotifikasi['current_stock'] = $stokBaikSaatIni;

                    if ($stokBaikSaatIni <= 0) {
                        $this->modelNotifikasi->createOutOfStockNotification($produkUntukNotifikasi);
                    } elseif ($stokBaikSaatIni <= (int) ($produk['min_stock'] ?? 0)) {
                        $this->modelNotifikasi->createLowStockNotification($produkUntukNotifikasi);
                    }
                }
            } catch (\Throwable $e) {
                log_message('error', 'Gagal kirim notifikasi distribusi: ' . $e->getMessage());
            }

            return $this->jsonResponse(['status' => true, 'message' => 'Barang berhasil didistribusikan dan stok telah terpotong.']);
        } catch (Exception $e) {
            $db->transRollback();
            log_message('error', 'Error saat distribusi: ' . $e->getMessage());
            return $this->jsonResponse(['status' => false, 'message' => 'Gagal distribusi: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Batalkan permintaan (AJAX)
     */
    public function batalkan($id)
    {
        $dataPermintaan = $this->modelPermintaan->find($id);
        if (!$dataPermintaan) return $this->jsonResponse(['status' => false, 'message' => 'Data tidak ditemukan'], 404);

        if ($dataPermintaan['status'] == 'distributed') {
            return $this->jsonResponse(['status' => false, 'message' => 'Tidak bisa membatalkan permintaan yang sudah didistribusikan.'], 400);
        }

        if ($this->modelPermintaan->update($id, ['status' => 'cancelled'])) {
            // Kirim notifikasi dibatalkan
            try {
                $this->modelNotifikasi->createRequestCancelledNotification($dataPermintaan);
            } catch (\Throwable $e) {
                log_message('error', 'Gagal kirim notifikasi cancel: ' . $e->getMessage());
            }
            return $this->jsonResponse(['status' => true, 'message' => 'Permintaan berhasil dibatalkan.']);
        }

        return $this->jsonResponse(['status' => false, 'message' => 'Gagal membatalkan permintaan.'], 500);
    }

    /**
     * Halaman publik - form permintaan barang (tanpa login)
     */
    public function askForm()
    {
        $produk = $this->modelProduk
            ->where('is_active', true)
            ->where('IFNULL(stock_baik, current_stock) >', 0, false)
            ->orderBy('name', 'ASC')
            ->findAll();

        $data = [
            'title'       => 'Ajukan Permintaan ATK | SIMATK',
            'daftarProduk' => $produk,
        ];

        return view('permintaan/ask', $data);
    }

    /**
     * Proses simpan permintaan publik
     */
    public function askStore()
    {
        $rules = [
            'borrower_name' => 'required|min_length[3]|max_length[150]',
            'borrower_unit' => 'required',
            'email'         => 'required|valid_email',
            'product_id'    => 'required',
            'quantity'      => 'required',
            'request_date'  => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $kodeResi = $this->generateKodeResiUnik();
            $requestId = $this->modelPermintaan->insert([
                'borrower_name'       => $this->request->getPost('borrower_name'),
                'borrower_identifier' => $this->request->getPost('borrower_identifier'),
                'borrower_unit'       => $this->request->getPost('borrower_unit'),
                'email'               => $this->request->getPost('email'),
                'receipt_code'        => $kodeResi,
                'request_date'        => $this->request->getPost('request_date') ?: date('Y-m-d'),
                'status'              => 'requested',
                'notes'               => $this->request->getPost('notes'),
            ]);

            $productIds = (array) $this->request->getPost('product_id');
            $quantities = (array) $this->request->getPost('quantity');

            foreach ($productIds as $index => $pid) {
                if (empty($pid) || empty($quantities[$index])) continue;
                $this->modelItemPermintaan->insert([
                    'request_id' => $requestId,
                    'product_id' => $pid,
                    'quantity'   => $quantities[$index],
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new Exception('Gagal menyimpan data ke database.');
            }

            $redirectUrl = $this->request->getPost('_redirect');

            // Kirim notifikasi ke admin
            try {
                $dataPermintaan = $this->modelPermintaan->find($requestId);
                if ($dataPermintaan) {
                    $this->modelNotifikasi->createNewRequestNotification($dataPermintaan);
                }
            } catch (\Throwable $e) {
                log_message('error', 'Gagal kirim notifikasi permintaan publik: ' . $e->getMessage());
            }

            if (!empty($redirectUrl)) {
                $redirectUrlDenganResi = $this->tambahkanResiKeUrl($redirectUrl, $kodeResi);
                return redirect()->to($redirectUrlDenganResi)
                    ->with('success', 'Permintaan berhasil diajukan.')
                    ->with('kode_resi', $kodeResi);
            }

            return redirect()->to('/ask/success')
                ->with('request_id', $requestId)
                ->with('borrower_name', $this->request->getPost('borrower_name'))
                ->with('kode_resi', $kodeResi);
        } catch (Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
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
        $referenceNo = trim((string) $this->request->getPost('reference_no'));
        $email = strtolower(trim((string) $this->request->getPost('email')));

        // Validasi input
        if (empty($referenceNo) || empty($email)) {
            return redirect()->back()->withInput()->with('error', 'Nomor referensi dan email harus diisi.');
        }

        $dataPermintaan = null;

        // Legacy fallback format: REQ-0001
        if (preg_match('/^REQ-(\d+)$/i', $referenceNo, $matches)) {
            $dataPermintaan = $this->modelPermintaan->find((int) $matches[1]);
        } else {
            // Format utama: receipt_code (contoh 20260410-120305)
            $dataPermintaan = $this->modelPermintaan->where('receipt_code', $referenceNo)->first();
        }

        if (!$dataPermintaan) {
            return redirect()->back()->withInput()->with('error', 'Permintaan tidak ditemukan.');
        }

        // Validasi email
        if (strtolower((string) ($dataPermintaan['email'] ?? '')) !== $email) {
            return redirect()->back()->withInput()->with('error', 'Email tidak sesuai dengan data permintaan.');
        }

        $requestId = (int) ($dataPermintaan['id'] ?? 0);
        if ($requestId <= 0) {
            return redirect()->back()->withInput()->with('error', 'Data permintaan tidak valid.');
        }

        $referenceNoDisplay = (string) ($dataPermintaan['receipt_code'] ?? '');
        if ($referenceNoDisplay === '') {
            $referenceNoDisplay = 'REQ-' . str_pad((string) $requestId, 4, '0', STR_PAD_LEFT);
        }

        // Ambil detail item permintaan
        $itemPermintaan = $this->modelItemPermintaan->where('request_id', $requestId)->findAll();

        // Enriched item dengan detail produk
        $itemEnriched = [];
        foreach ($itemPermintaan as $item) {
            $produk = $this->modelProduk->find($item['product_id']);
            $itemEnriched[] = [
                'item' => $item,
                'produk' => $produk
            ];
        }

        // Tentukan badge status
        $statusBadges = [
            'requested'   => ['text' => 'Menunggu Persetujuan', 'color' => 'warning', 'icon' => 'hourglass-split'],
            'approved'    => ['text' => 'Disetujui', 'color' => 'info', 'icon' => 'check-circle'],
            'distributed' => ['text' => 'Sudah Dikirim', 'color' => 'success', 'icon' => 'check2-all'],
            'cancelled'   => ['text' => 'Dibatalkan', 'color' => 'danger', 'icon' => 'x-circle'],
        ];

        $data = [
            'title'              => 'Lacak Permintaan ATK | SIMATK',
            'referenceNo'        => $referenceNoDisplay,
            'permintaan'         => $dataPermintaan,
            'itemPermintaan'     => $itemEnriched,
            'statusBadges'       => $statusBadges,
        ];

        return view('permintaan/track_result', $data);
    }
}
