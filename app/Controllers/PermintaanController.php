<?php

namespace App\Controllers;

use App\Models\PermintaanModel;
use App\Models\ItemPermintaanModel;
use App\Models\BarangModel;
use App\Models\NotifikasiModel;
use App\Services\PermintaanService;
use Exception;

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
        $builder = $this->modelPermintaan->orderBy('created_at', 'DESC');

        if ($status) {
            $builder->where('status', $status);
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
        $result = $this->permintaanService->batalkanPermintaan((int)$id);

        return $this->jsonResponse($result, $result['success'] ? 200 : 400);
    }

    /**
     * Halaman publik - form permintaan barang (tanpa login)
     */
    public function askForm()
    {
        $barang = $this->modelBarang
            ->where('is_active', true)
            ->where('IFNULL(stock_baik, current_stock) >', 0, false)
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

            return redirect()->to('/ask/success')
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
}
