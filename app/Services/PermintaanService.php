<?php

namespace App\Services;

use App\Models\BarangModel;
use App\Models\ItemPermintaanModel;
use App\Models\MutasiStokModel;
use App\Models\NotifikasiModel;
use App\Models\PermintaanModel;
use CodeIgniter\Database\ConnectionInterface;
use Exception;

class PermintaanService
{
    protected PermintaanModel $modelPermintaan;
    protected ItemPermintaanModel $modelItemPermintaan;
    protected BarangModel $modelBarang;
    protected MutasiStokModel $modelMutasiStok;
    protected NotifikasiModel $modelNotifikasi;
    protected ConnectionInterface $db;

    public function __construct(
        PermintaanModel $permintaanModel,
        ItemPermintaanModel $itemPermintaanModel,
        BarangModel $barangModel,
        MutasiStokModel $mutasiStokModel,
        NotifikasiModel $notifikasiModel,
        ?ConnectionInterface $db = null
    ) {
        $this->modelPermintaan     = $permintaanModel;
        $this->modelItemPermintaan = $itemPermintaanModel;
        $this->modelBarang         = $barangModel;
        $this->modelMutasiStok     = $mutasiStokModel;
        $this->modelNotifikasi     = $notifikasiModel;
        $this->db                  = $db ?? \Config\Database::connect();
    }

    /**
     * Membuat permintaan baru, termasuk item dan notifikasi.
     *
     * @param array $data Data permintaan dari request->getPost()
     * @return array ['success' => bool, 'message' => string, 'data' => array]
     */
    public function buatPermintaan(array $data): array
    {
        $this->db->transStart();

        try {
            $kodeResi = $this->generateKodeResiUnik();

            $requestData = [
                'borrower_name'       => $data['borrower_name'],
                'borrower_identifier' => $data['borrower_identifier'] ?? null,
                'borrower_unit'       => $data['borrower_unit'],
                'email'               => $data['email'],
                'receipt_code'        => $kodeResi,
                'request_date'        => $data['request_date'] ?: date('Y-m-d'),
                'status'              => 'requested',
                'notes'               => $data['notes'] ?? null,
            ];

            $requestId = $this->modelPermintaan->insert($requestData);

            if (!$requestId) {
                throw new Exception('Gagal menyimpan data permintaan.');
            }

            $this->saveItems((int) $requestId, (array)($data['product_id'] ?? []), (array)($data['quantity'] ?? []));

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new Exception('Gagal menyimpan data ke database.');
            }

            $this->kirimNotifikasiPermintaanBaru($requestId, 'baru');

            return [
                'success' => true,
                'message' => 'Permintaan berhasil dibuat.',
                'data'    => ['request_id' => (int) $requestId, 'receipt_code' => $kodeResi]
            ];
        } catch (Exception $e) {
            $this->db->transRollback();
            return ['success' => false, 'message' => $e->getMessage(), 'data' => []];
        }
    }

    /**
     * Menyetujui sebuah permintaan.
     *
     * @param int $id ID Permintaan
     * @return array ['success' => bool, 'message' => string]
     */
    public function setujuiPermintaan(int $id): array
    {
        $dataPermintaan = $this->modelPermintaan->find($id);
        if (!$dataPermintaan) {
            return ['success' => false, 'message' => 'Data permintaan tidak ditemukan.'];
        }

        if ($this->modelPermintaan->update($id, ['status' => 'approved'])) {
            try {
                $this->modelNotifikasi->createRequestApprovedNotification($dataPermintaan);
            } catch (\Throwable $e) {
                log_message('error', 'Gagal kirim notifikasi approve: ' . $e->getMessage());
            }
            return ['success' => true, 'message' => 'Permintaan berhasil disetujui.'];
        }

        return ['success' => false, 'message' => 'Gagal memperbarui status permintaan.'];
    }

    /**
     * Membatalkan sebuah permintaan.
     *
     * @param int $id ID Permintaan
     * @return array ['success' => bool, 'message' => string]
     */
    public function batalkanPermintaan(int $id): array
    {
        $dataPermintaan = $this->modelPermintaan->find($id);
        if (!$dataPermintaan) {
            return ['success' => false, 'message' => 'Data tidak ditemukan'];
        }

        if ($dataPermintaan['status'] === 'distributed') {
            return ['success' => false, 'message' => 'Tidak bisa membatalkan permintaan yang sudah didistribusikan.'];
        }

        if ($this->modelPermintaan->update($id, ['status' => 'cancelled'])) {
            try {
                $this->modelNotifikasi->createRequestCancelledNotification($dataPermintaan);
            } catch (\Throwable $e) {
                log_message('error', 'Gagal kirim notifikasi cancel: ' . $e->getMessage());
            }
            return ['success' => true, 'message' => 'Permintaan berhasil dibatalkan.'];
        }

        return ['success' => false, 'message' => 'Gagal membatalkan permintaan.'];
    }

    /**
     * Mendistribusikan item dari sebuah permintaan.
     *
     * @param int $id ID Permintaan
     * @param int $userId ID User yang melakukan aksi
     * @param string $userName Nama User yang melakukan aksi
     * @return array ['success' => bool, 'message' => string]
     */
    public function distribusikanPermintaan(int $id, int $userId, string $userName): array
    {
        try {
            $dataPermintaan = $this->modelPermintaan->find($id);
            $validation = $this->validateDistribusi($dataPermintaan, $id);
            if (!$validation['success']) {
                return $validation;
            }

            // Jika status sudah distributed, anggap sukses.
            if (($dataPermintaan['status'] ?? '') === 'distributed') {
                return ['success' => true, 'message' => 'Permintaan ini sudah berstatus didistribusikan.'];
            }

            $daftarItem = $this->modelItemPermintaan->where('request_id', $id)->findAll();
            if (empty($daftarItem)) {
                return ['success' => false, 'message' => 'Tidak ada item untuk didistribusikan'];
            }

            return $this->handleDistribusi($id, $userId, $userName, $daftarItem);
        } catch (Exception $e) {
            log_message('error', 'Error saat distribusi: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan internal: ' . $e->getMessage()];
        }
    }

    /**
     * Mengisi kode resi yang kosong pada data lama.
     */
    public function isiKodeResiKosong(): void
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
            $timestamp = $timestamp ?: time();

            $kandidat = $this->generateKodeResiDariTimestamp($timestamp);
            $kodeUnik = $this->pastikanKodeResiUnik($kandidat, (int) $row['id'], $timestamp);

            $this->modelPermintaan->update((int) $row['id'], ['receipt_code' => $kodeUnik]);
        }
    }

    // --- Private Helper Methods ---

    /**
     * Validasi awal sebelum proses distribusi.
     */
    private function validateDistribusi(?array $dataPermintaan, int $id): array
    {
        if (!$dataPermintaan) {
            return ['success' => false, 'message' => 'Data tidak ditemukan'];
        }

        $statusSaatIni = (string) ($dataPermintaan['status'] ?? '');
        if ($statusSaatIni === 'cancelled') {
            return ['success' => false, 'message' => 'Permintaan yang sudah dibatalkan tidak bisa didistribusikan.'];
        }
        if ($statusSaatIni !== 'approved' && $statusSaatIni !== 'distributed') {
            return ['success' => false, 'message' => 'Permintaan belum disetujui'];
        }

        $existingDistribution = $this->modelMutasiStok->where('type', 'OUT')->where('reference_no', 'REQ-' . $id)->countAllResults();
        if ($existingDistribution > 0 && $statusSaatIni !== 'distributed') {
            $this->modelPermintaan->update($id, ['status' => 'distributed']);
            return ['success' => true, 'message' => 'Permintaan sudah pernah didistribusikan. Status disinkronkan.'];
        }

        return ['success' => true, 'message' => 'Validasi OK'];
    }

    /**
     * Menangani proses utama distribusi (transaksi DB, mutasi, notifikasi).
     */
    private function handleDistribusi(int $id, int $userId, string $userName, array $daftarItem): array
    {
        $errorStok = $this->cekStok($daftarItem);
        if (!empty($errorStok)) {
            return ['success' => false, 'message' => 'Stok tidak mencukupi:<br>• ' . implode('<br>• ', $errorStok)];
        }

        $this->db->transStart();
        try {
            // Cek ulang stok tepat sebelum mutasi untuk mencegah race condition.
            $errorStokTerbaru = $this->cekStok($daftarItem);
            if (!empty($errorStokTerbaru)) {
                throw new Exception('Distribusi dibatalkan karena stok berubah dan tidak mencukupi:<br>• ' . implode('<br>• ', $errorStokTerbaru));
            }

            $this->prosesMutasi($daftarItem, $id, $userId, $userName);
            $this->modelPermintaan->update($id, ['status' => 'distributed']);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new Exception('Gagal memproses mutasi stok. Silakan coba lagi.');
            }

            $this->cekNotifikasiStok($daftarItem);
            return ['success' => true, 'message' => 'Barang berhasil didistribusikan dan stok telah terpotong.'];
        } catch (Exception $e) {
            $this->db->transRollback();
            // Re-throw to be caught by the main method
            throw $e;
        }
    }

    private function saveItems(int $requestId, array $productIds, array $quantities): void
    {
        foreach ($productIds as $index => $pid) {
            if (empty($pid) || empty($quantities[$index])) {
                continue;
            }

            $this->modelItemPermintaan->insert([
                'request_id' => $requestId,
                'product_id' => $pid,
                'quantity'   => $quantities[$index],
            ]);
        }
    }

    private function kirimNotifikasiPermintaanBaru(int $requestId, string $context): void
    {
        try {
            $dataPermintaan = $this->modelPermintaan->find($requestId);
            if ($dataPermintaan) {
                $this->modelNotifikasi->createNewRequestNotification($dataPermintaan);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Gagal kirim notifikasi permintaan ' . $context . ': ' . $e->getMessage());
        }
    }

    private function cekStok(array $items): array
    {
        $errors = [];
        foreach ($items as $item) {
            $barang = $this->modelBarang->find($item['product_id']);
            if (!$barang) {
                $errors[] = "Barang ID {$item['product_id']} tidak ditemukan";
                continue;
            }
            $stok = (int) ($barang['stock_baik'] ?? $barang['current_stock']);
            if ($stok < (int) $item['quantity']) {
                $errors[] = "{$barang['name']}: stok baik tersedia {$stok}, diminta {$item['quantity']}";
            }
        }
        return $errors;
    }

    private function prosesMutasi(array $items, int $requestId, int $userId, string $userName): void
    {
        foreach ($items as $item) {
            $this->modelMutasiStok->buatMutasi([
                'product_id'   => $item['product_id'],
                'type'         => 'OUT',
                'quantity'     => $item['quantity'],
                'notes'        => 'Distribusi ATK - No. Permintaan: #' . $requestId . ' oleh ' . ($userName ?: 'Admin'),
                'reference_no' => 'REQ-' . $requestId,
                'created_by'   => $userId,
            ]);
        }
    }

    private function cekNotifikasiStok(array $items): void
    {
        foreach ($items as $item) {
            $barang = $this->modelBarang->find($item['product_id']);
            if (!$barang) continue;

            $stokBaikSaatIni = (int) ($barang['stock_baik'] ?? $barang['current_stock']);
            $barang['current_stock'] = $stokBaikSaatIni;

            if ($stokBaikSaatIni <= 0) {
                $this->modelNotifikasi->createOutOfStockNotification($barang);
            } elseif ($stokBaikSaatIni <= (int) ($barang['min_stock'] ?? 0)) {
                $this->modelNotifikasi->createLowStockNotification($barang);
            }
        }
    }

    private function generateKodeResiUnik(): string
    {
        do {
            $kode = date('Ymd-His');
            $exists = $this->modelPermintaan->where('receipt_code', $kode)->countAllResults() > 0;
            if ($exists) sleep(1);
        } while ($exists);
        return $kode;
    }

    private function generateKodeResiDariTimestamp(int $timestamp): string
    {
        return date('Ymd-His', $timestamp);
    }

    private function pastikanKodeResiUnik(string $kandidat, int $excludeId, int $baseTimestamp): string
    {
        $kode = $kandidat;
        $ts = $baseTimestamp;
        while ($this->modelPermintaan->where('receipt_code', $kode)->where('id !=', $excludeId)->countAllResults() > 0) {
            $ts++;
            $kode = $this->generateKodeResiDariTimestamp($ts);
        }
        return $kode;
    }
}
