<?php

namespace App\Controllers;

use App\Models\ProdukModel;
use App\Models\KategoriModel;
use App\Models\MutasiStokModel;
use App\Models\NotifikasiModel;
use App\Controllers\BaseController;
use Exception;

/**
 * StokController - Controller untuk mengelola stok barang
 *
 * Menangani:
 * - Barang masuk (stokMasuk)
 * - Barang keluar (stokKeluar)
 * - Mutasi stok (mutasi)
 * - Riwayat stok (riwayat)
 * - Penyesuaian stok (penyesuaian)
 * - Peringatan stok (peringatan)
 */
class StokController extends BaseController
{
    protected ProdukModel $modelProduk;
    protected KategoriModel $modelKategori;
    protected MutasiStokModel $modelMutasiStok;
    protected NotifikasiModel $modelNotifikasi;

    public function __construct()
    {
        $this->modelProduk        = new ProdukModel();
        $this->modelKategori       = new KategoriModel();
        $this->modelMutasiStok  = new MutasiStokModel();
        $this->modelNotifikasi   = new NotifikasiModel();
    }

    /**
     * Halaman Barang Masuk
     */
    public function stockIn()
    {
        $this->setPageData('Barang Masuk', 'Input stok barang masuk ke gudang / inventory');

        $products   = $this->modelProduk->getProdukDenganKategori();
        $categories = $this->modelKategori->getKategoriAktif();

        $recentHistory = $this->modelMutasiStok->select('stock_movements.*, products.name as product_name, products.sku as product_sku')
            ->join('products', 'products.id = stock_movements.product_id')
            ->where('stock_movements.type', 'IN')
            ->orderBy('stock_movements.created_at', 'DESC')
            ->limit(10)
            ->findAll();

        $data = [
            'daftarProduk'    => $products,
            'daftarKategori'  => $categories,
            'riwayatTerakhir' => $recentHistory,
            'produkTerpilih'  => $this->request->getGet('product')
        ];

        return $this->render('stock/in', $data);
    }

    /**
     * Proses simpan Barang Masuk
     */
    public function storeStockIn()
    {
        $rules = [
            'movements' => 'required',
            'movements.*.product_id' => 'required',
            'movements.*.quantity'   => 'required|integer|greater_than[0]',
            'movements.*.damaged_quantity' => 'permit_empty|is_natural',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $movementData = $this->request->getPost('movements');
        $globalNotes  = $this->request->getPost('global_notes');
        $reference    = $this->request->getPost('reference_no') ?: 'IN-' . time();
        $redirectAfter = trim((string) $this->request->getPost('_redirect'));
        $successRedirect = '/stock/in';
        if ($redirectAfter !== '' && str_starts_with($redirectAfter, '/')) {
            $successRedirect = $redirectAfter;
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $successCount = 0;

            foreach ($movementData as $m) {
                if (empty($m['product_id']) || empty($m['quantity'])) continue;

                $this->modelMutasiStok->buatMutasi([
                    'product_id'   => $m['product_id'],
                    'type'         => 'IN',
                    'quantity'     => $m['quantity'],
                    'damaged_quantity' => (int) ($m['damaged_quantity'] ?? 0),
                    'reference_no' => $reference,
                    'notes'        => ($m['notes'] ?? '') ?: $globalNotes,
                    'created_by'   => session()->get('userId') ?: null
                ]);
                $successCount++;
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new Exception('Gagal menyimpan mutasi stok.');
            }

            // Kirim notifikasi barang masuk
            try {
                foreach ($movementData as $m) {
                    if (empty($m['product_id'])) continue;
                    $produk = $this->modelProduk->find($m['product_id']);
                    if ($produk) {
                        $qtyNotifikasi = (int) ($m['quantity'] ?? 0) + (int) ($m['damaged_quantity'] ?? 0);
                        $this->modelNotifikasi->createStockInNotification(
                            (string) $produk['name'],
                            $qtyNotifikasi,
                            $reference
                        );
                    }
                }
            } catch (\Throwable $e) {
                log_message('error', 'Gagal kirim notifikasi stok masuk: ' . $e->getMessage());
            }

            return redirect()->to($successRedirect)->with('success', "Berhasil memproses $successCount item barang masuk.");
        } catch (Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Halaman Barang Keluar
     */
    public function stockOut()
    {
        $this->setPageData('Barang Keluar', 'Input pengeluaran stok barang dari gudang');

        $products   = $this->modelProduk
            ->where('IFNULL(stock_baik, current_stock) >', 0, false)
            ->orderBy('name', 'ASC')
            ->findAll();
        $categories = $this->modelKategori->getKategoriAktif();

        $recentHistory = $this->modelMutasiStok->select('stock_movements.*, products.name as product_name, products.sku as product_sku')
            ->join('products', 'products.id = stock_movements.product_id')
            ->where('stock_movements.type', 'OUT')
            ->orderBy('stock_movements.created_at', 'DESC')
            ->limit(10)
            ->findAll();

        $data = [
            'daftarProduk'    => $products,
            'daftarKategori'  => $categories,
            'riwayatTerakhir' => $recentHistory,
            'produkTerpilih'  => $this->request->getGet('product')
        ];

        return $this->render('stock/out', $data);
    }

    /**
     * Proses simpan Barang Keluar
     */
    public function storeStockOut()
    {
        $rules = [
            'movements' => 'required',
            'movements.*.product_id' => 'required',
            'movements.*.quantity'   => 'required|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $movementData = $this->request->getPost('movements');
        $globalNotes  = $this->request->getPost('global_notes');
        $reference    = $this->request->getPost('reference_no') ?: 'OUT-' . time();

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $successCount = 0;

            foreach ($movementData as $m) {
                if (empty($m['product_id']) || empty($m['quantity'])) continue;

                $this->modelMutasiStok->buatMutasi([
                    'product_id'   => $m['product_id'],
                    'type'         => 'OUT',
                    'quantity'     => $m['quantity'],
                    'reference_no' => $reference,
                    'notes'        => ($m['notes'] ?? '') ?: $globalNotes,
                    'created_by'   => session()->get('userId') ?: null
                ]);
                $successCount++;
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new Exception('Gagal menyimpan mutasi keluar.');
            }

            // Kirim notifikasi barang keluar + cek stok rendah/habis
            try {
                foreach ($movementData as $m) {
                    if (empty($m['product_id'])) continue;
                    $produk = $this->modelProduk->find($m['product_id']);
                    if (!$produk) continue;

                    $this->modelNotifikasi->createStockOutNotification(
                        (string) $produk['name'],
                        (int)$m['quantity'],
                        $reference
                    );

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
                log_message('error', 'Gagal kirim notifikasi stok keluar: ' . $e->getMessage());
            }

            return redirect()->to('/stock/out')->with('success', "Berhasil memproses $successCount item barang keluar.");
        } catch (Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Halaman Mutasi Stok (tampilan gabungan IN/OUT)
     */
    public function movements()
    {
        $currentType = $this->request->getGet('type') ?: 'IN';
        if (!in_array($currentType, ['IN', 'OUT'], true)) {
            $currentType = 'IN';
        }

        $typeLabel = $currentType === 'IN' ? 'Mode Barang Masuk' : 'Mode Barang Keluar';
        $this->setPageData(
            $currentType === 'IN' ? 'Barang Masuk' : 'Barang Keluar',
            'Kelola pergerakan stok barang ' . ($currentType === 'IN' ? 'masuk' : 'keluar')
        );

        $filters = [
            'product'    => $this->request->getGet('product'),
            'category'   => $this->request->getGet('category'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date'   => $this->request->getGet('end_date'),
        ];

        $builder = $this->modelMutasiStok
            ->select('stock_movements.*, products.name as product_name, products.sku as product_sku, categories.name as category_name')
            ->join('products', 'products.id = stock_movements.product_id')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('stock_movements.type', $currentType);

        if (!empty($filters['product'])) {
            $builder->where('stock_movements.product_id', $filters['product']);
        }
        if (!empty($filters['category'])) {
            $builder->where('products.category_id', $filters['category']);
        }
        if (!empty($filters['start_date'])) {
            $builder->where('DATE(stock_movements.created_at) >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $builder->where('DATE(stock_movements.created_at) <=', $filters['end_date']);
        }

        $recentMovements = $builder->orderBy('stock_movements.created_at', 'DESC')
            ->limit(20)
            ->findAll();

        // Statistik
        $statsBuilder = $this->modelMutasiStok->where('type', $currentType);
        $totalTransactions = $statsBuilder->countAllResults(false);
        $totalQuantity     = (int) $this->modelMutasiStok
            ->selectSum('quantity')
            ->where('type', $currentType)
            ->first()['quantity'];

        $products   = $this->modelProduk->orderBy('name', 'ASC')->findAll();
        $categories = $this->modelKategori->getKategoriAktif();

        $data = [
            'current_type'     => $currentType,
            'stats'            => [
                'total_transactions' => $totalTransactions,
                'total_quantity'     => $totalQuantity,
                'type_label'         => $typeLabel,
            ],
            'filters'          => $filters,
            'recent_movements' => $recentMovements,
            'products'         => $products,
            'categories'       => $categories,
        ];

        return $this->render('stock/movements', $data);
    }

    /**
     * Halaman Riwayat Stok
     */
    public function history()
    {
        $this->setPageData('Riwayat Stok', 'History pergerakan keluar dan masuk barang');

        $filters = [
            'product_id' => $this->request->getGet('product'),
            'type'       => $this->request->getGet('type'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date'   => $this->request->getGet('end_date')
        ];

        $movements = $this->modelMutasiStok->getMutasiDenganProduk(0, $filters);

        $data = [
            'daftarMutasi'   => $movements,
            'daftarProduk'   => $this->modelProduk->orderBy('name', 'ASC')->findAll(),
            'filterProduk'   => $filters['product_id'],
            'filterTipe'     => $filters['type'],
            'tglMulai'       => $filters['start_date'],
            'tglSelesai'     => $filters['end_date']
        ];

        return $this->render('stock/history', $data);
    }

    /**
     * Halaman Penyesuaian Stok
     */
    public function adjustment()
    {
        $this->setPageData('Penyesuaian Stok', 'Koreksi stok barang sesuai kondisi fisik gudang');

        $data = [
            'daftarProduk' => $this->modelProduk->getProdukDenganKategori()
        ];

        return $this->render('stock/adjustment', $data);
    }

    /**
     * Simpan Penyesuaian Stok
     */
    public function storeAdjustment()
    {
        $adjustments = $this->request->getPost('adjustments');
        $globalNotes = $this->request->getPost('global_notes') ?: 'Penyesuaian stok manual';
        $reference = $this->request->getPost('reference_no') ?: 'ADJ-' . time();

        if (empty($adjustments)) {
            return redirect()->back()->with('error', 'Tidak ada data penyesuaian yang dikirim.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $successCount = 0;
            foreach ($adjustments as $p) {
                if (!isset($p['product_id'])) continue;

                $stokBaik = isset($p['stock_baik'])
                    ? max(0, (int) $p['stock_baik'])
                    : max(0, (int) ($p['new_stock'] ?? 0));
                $stokRusak = isset($p['stock_rusak'])
                    ? max(0, (int) $p['stock_rusak'])
                    : 0;

                $this->modelMutasiStok->buatMutasi([
                    'product_id'   => $p['product_id'],
                    'type'         => 'ADJUSTMENT',
                    'quantity'     => $stokBaik + $stokRusak,
                    'adjusted_good_stock'    => $stokBaik,
                    'adjusted_damaged_stock' => $stokRusak,
                    'reference_no' => $reference,
                    'notes'        => ($p['notes'] ?? '') ?: $globalNotes,
                    'created_by'   => session()->get('userId') ?: null
                ]);
                $successCount++;
            }

            $db->transComplete();
            if ($db->transStatus() === false) throw new Exception('Gagal menyimpan penyesuaian.');

            // Cek stok rendah/habis setelah penyesuaian
            try {
                foreach ($adjustments as $p) {
                    if (!isset($p['product_id'])) continue;
                    $produk = $this->modelProduk->find($p['product_id']);
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
                log_message('error', 'Gagal kirim notifikasi penyesuaian: ' . $e->getMessage());
            }

            return redirect()->to('/stock/adjustment')->with('success', "Berhasil menyesuaikan $successCount item stok.");
        } catch (Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Halaman Peringatan Stok
     */
    public function alerts()
    {
        $this->setPageData('Peringatan Stok', 'Daftar barang yang stoknya menipis atau habis');

        $lowStockProducts = $this->modelProduk->getProdukStokRendah();
        $outOfStockProducts = $this->modelProduk
            ->where('is_active', true)
            ->where('IFNULL(stock_baik, current_stock) <= 0', null, false)
            ->findAll();
        $totalActive = $this->modelProduk->where('is_active', true)->countAllResults(false);

        $data = [
            'stokRendah' => $lowStockProducts,
            'stokHabis'  => $outOfStockProducts,
            'stats' => [
                'out_of_stock'  => count($outOfStockProducts),
                'low_stock'     => count($lowStockProducts),
                'normal_stock'  => $totalActive - count($outOfStockProducts) - count($lowStockProducts),
            ],
        ];

        return $this->render('stock/alerts', $data);
    }

    /**
     * Ekspor Riwayat Stok
     */
    public function exportHistory($format = 'excel')
    {
        $filters = [
            'product_id' => $this->request->getGet('product'),
            'type'       => $this->request->getGet('type'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date'   => $this->request->getGet('end_date')
        ];

        $movements = $this->modelMutasiStok->getMutasiDenganProduk(0, $filters);

        if ($format === 'excel') {
            return $this->exportHistoryExcel($movements);
        } else {
            return $this->exportHistoryPDF($movements);
        }
    }

    /**
     * Ekspor riwayat stok ke Excel
     */
    private function exportHistoryExcel(array $movements)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'RIWAYAT MUTASI STOK INVENTORY');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Dicetak pada: ' . date('d/m/Y H:i'));
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headers = ['No', 'Tanggal & Waktu', 'Produk', 'Kode Barang', 'Tipe', 'Jumlah', 'Stok Sisa', 'Referensi/Ket'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '4', $header);
            $sheet->getStyle($col . '4')->getFont()->setBold(true);
            $sheet->getStyle($col . '4')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E9ECEF');
            $col++;
        }

        $row = 5;
        foreach ($movements as $index => $mut) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, date('d/m/Y H:i', strtotime($mut['created_at'])));
            $sheet->setCellValue('C' . $row, $mut['product_name']);
            $sheet->setCellValue('D' . $row, $mut['product_sku']);
            $sheet->setCellValue('E' . $row, $mut['type']);
            $sheet->setCellValue('F' . $row, ($mut['type'] == 'IN' ? '+' : ($mut['type'] == 'OUT' ? '-' : '±')) . $mut['quantity']);
            $sheet->setCellValue('G' . $row, $mut['current_stock']);
            $sheet->setCellValue('H' . $row, ($mut['reference_no'] ? "Ref: " . $mut['reference_no'] . " | " : "") . ($mut['notes'] ?: '-'));
            $row++;
        }

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Riwayat_Stok_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Ekspor riwayat stok ke PDF
     */
    private function exportHistoryPDF(array $movements)
    {
        $html = view('stock/history_pdf', ['movements' => $movements]);

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'Riwayat_Stok_' . date('YmdHis') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }

    /**
     * Ambil detail stok satu produk (AJAX helper)
     */
    public function getProductStock($id)
    {
        $product = $this->modelProduk->getProdukDenganKategoriById((int) $id);
        if (!$product) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        $currentStock = (int) ($product['current_stock'] ?? 0);
        $minStock     = (int) ($product['min_stock'] ?? 0);

        $stockStatus = 'normal';
        if ($currentStock <= 0) {
            $stockStatus = 'habis';
        } elseif ($currentStock <= $minStock) {
            $stockStatus = 'rendah';
        }

        return $this->jsonResponse([
            'status' => true,
            'data'   => [
                'id'            => (int) $product['id'],
                'name'          => $product['name'],
                'sku'           => $product['sku'],
                'unit'          => $product['unit'],
                'current_stock' => $currentStock,
                'stock_baik'    => (int) ($product['stock_baik'] ?? $currentStock),
                'stock_rusak'   => (int) ($product['stock_rusak'] ?? 0),
                'min_stock'     => $minStock,
                'stock_status'  => $stockStatus,
            ],
        ]);
    }
}
