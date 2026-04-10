<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\ProdukModel;
use App\Models\KategoriModel;
use App\Models\KodeBarangModel;
use App\Models\MutasiStokModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Exception;

/**
 * ProdukController - Controller untuk mengelola produk/barang
 *
 * Relasi:
 * - Produk memiliki Kategori
 * - PergerakanStok terkait Produk
 */
class ProdukController extends BaseController
{
    protected ProdukModel $modelProduk;
    protected KategoriModel $modelKategori;
    protected KodeBarangModel $modelKodeBarang;
    protected MutasiStokModel $modelMutasiStok;

    public function __construct()
    {
        $this->modelProduk = new ProdukModel();
        $this->modelKategori = new KategoriModel();
        $this->modelKodeBarang = new KodeBarangModel();
        $this->modelMutasiStok = new MutasiStokModel();
    }

    /**
     * Tampilkan daftar produk
     */
    public function index()
    {
        $this->setPageData('Daftar Produk', 'Manajemen stok dan inventaris barang');

        $search      = $this->request->getGet('search');
        $category    = $this->request->getGet('category');
        $stockStatus = $this->request->getGet('stock_status');

        $products = $this->modelProduk->getProdukTerfilter([
            'search'       => $search,
            'category'     => $category,
            'stock_status' => $stockStatus
        ]);

        $categories = $this->modelKategori->getKategoriAktif();

        $data = [
            'daftarProduk'   => $products,
            'daftarKategori' => $categories,
            'filterCari'     => $search,
            'filterKategori' => $category,
            'filterStok'     => $stockStatus,
            'totalItem'      => count($products)
        ];

        return $this->render('produk/index', $data);
    }

    /**
     * Form tambah produk baru
     */
    public function tambah()
    {
        $this->setPageData('Tambah Produk', 'Input data barang baru ke sistem');

        $data = [
            'produk' => [
                'name'          => '',
                'sku'           => '',
                'category_id'   => '',
                'description'   => '',
                'price'         => 0,
                'cost_price'    => 0,
                'min_stock'     => 5,
                'current_stock' => 0,
                'unit'          => 'Pcs',
                'is_active'     => 1
            ],
            'daftarKategori' => $this->modelKategori->getKategoriAktif(),
        ];

        return $this->render('produk/create', $data);
    }

    /**
     * Simpan produk baru atau perbarui produk yang sudah ada
     */
    public function simpan()
    {
        $productId = $this->request->getPost('id');
        $isUpdate = !empty($productId);

        $rules = [
            'name'          => 'required|min_length[3]|max_length[255]',
            'sku'           => "required|min_length[3]|max_length[50]|is_unique[products.sku,id,{$productId}]",
            'category_id'   => 'required|integer',
            'price'         => 'required|decimal',
            'cost_price'    => 'permit_empty|decimal',
            'min_stock'     => 'required|integer',
            'unit'          => 'required|max_length[20]'
        ];

        if (!$isUpdate) {
            $rules['initial_stock'] = 'permit_empty|integer';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Mohon periksa kembali data yang Anda masukkan.');
        }

        $requestedSku = strtoupper(trim((string) $this->request->getPost('sku')));
        $categoryId = (int) $this->request->getPost('category_id');
        $resolvedSku = $this->tentukanKodeProdukDenganCadangan($requestedSku, $categoryId);

        $duplicateSku = $this->modelProduk->where('sku', $resolvedSku)->first();
        if ($duplicateSku && (!$isUpdate || (int) $duplicateSku['id'] !== (int) $productId)) {
            return redirect()->back()->withInput()->with('error', 'Kode barang ' . $resolvedSku . ' sudah digunakan. Silakan gunakan kode lain.');
        }

        $payload = [
            'name'          => $this->request->getPost('name'),
            'sku'           => $resolvedSku,
            'category_id'   => $this->request->getPost('category_id'),
            'description'   => $this->request->getPost('description'),
            'price'         => (float) $this->request->getPost('price'),
            'cost_price'    => (float) $this->request->getPost('cost_price') ?: 0,
            'min_stock'     => (int) $this->request->getPost('min_stock'),
            'unit'          => $this->request->getPost('unit'),
            'is_active'     => $this->request->getPost('is_active') ?? 1,
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            if ($isUpdate) {
                $this->modelProduk->update($productId, $payload);
            } else {
                $initialStock = (int) $this->request->getPost('initial_stock') ?: 0;
                $payload['current_stock'] = $initialStock;
                $payload['stock_baik'] = $initialStock;
                $payload['stock_rusak'] = 0;

                $newProductId = $this->modelProduk->insert($payload);

                if ($initialStock > 0) {
                    $this->modelMutasiStok->insert([
                        'product_id'   => $newProductId,
                        'type'         => 'IN',
                        'quantity'     => $initialStock,
                        'notes'        => 'Stok awal produk baru',
                        'reference_no' => 'INIT-' . time(),
                        'created_by'   => session()->get('userId') ?: null
                    ]);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new Exception('Gagal menyimpan ke database');
            }

            $message = $isUpdate ? 'Produk berhasil diperbarui.' : 'Produk berhasil ditambahkan.';
            return redirect()->to('/products')->with('success', $message);
        } catch (Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Jika kode barang tidak ditemukan di referensi, gunakan kode "lainnya" (akhiran 999)
     * berdasarkan kategori yang dipilih. Contoh: 8010101000 -> 8010101999.
     */
    private function tentukanKodeProdukDenganCadangan(string $requestedSku, int $categoryId): string
    {
        $requestedSku = preg_replace('/\D+/', '', $requestedSku) ?? '';
        if ($requestedSku === '') {
            return '';
        }

        $exactKode = $this->modelKodeBarang->where('kode', $requestedSku)->first();
        if ($exactKode) {
            return $requestedSku;
        }

        if ($categoryId <= 0) {
            return $requestedSku;
        }

        $category = $this->modelKategori->find($categoryId);
        if (!$category || empty($category['name'])) {
            return $requestedSku;
        }

        $categoryName = trim((string) $category['name']);
        if ($categoryName === '') {
            return $requestedSku;
        }

        $categoryKode = $this->modelKodeBarang
            ->where('LOWER(nama)', strtolower($categoryName))
            ->first();

        if (!$categoryKode) {
            $categoryKode = $this->modelKodeBarang
                ->like('nama', $categoryName)
                ->orderBy('kode', 'ASC')
                ->first();
        }

        $baseCode = (string) ($categoryKode['kode'] ?? '');
        $baseCode = preg_replace('/\D+/', '', $baseCode) ?? '';

        if (strlen($baseCode) >= 10) {
            return substr($baseCode, 0, 7) . '999';
        }

        return $requestedSku;
    }

    /**
     * Tampilkan detail produk
     */
    public function detail($id)
    {
        $product = $this->modelProduk->getProdukDenganKategoriById((int)$id);

        if (!$product) {
            return redirect()->to('/products')->with('error', 'Produk tidak ditemukan.');
        }

        $this->setPageData('Detail Produk', $product['name']);

        $stockHistory = $this->modelMutasiStok->where('product_id', $id)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->findAll();

        $stats = [
            'total_masuk'  => $this->modelMutasiStok->where('product_id', $id)->where('type', 'IN')->selectSum('quantity', 'total')->first()['total'] ?? 0,
            'total_keluar' => $this->modelMutasiStok->where('product_id', $id)->where('type', 'OUT')->selectSum('quantity', 'total')->first()['total'] ?? 0,
        ];

        $data = [
            'produk'      => $product,
            'riwayatStok' => $stockHistory,
            'statistik'   => $stats
        ];

        return $this->render('produk/show', $data);
    }

    /**
     * Form ubah/edit produk
     */
    public function ubah($id)
    {
        $product = $this->modelProduk->find($id);

        if (!$product) {
            return redirect()->to('/products')->with('error', 'Produk tidak ditemukan.');
        }

        $this->setPageData('Edit Produk', $product['name']);

        $data = [
            'produk'         => $product,
            'daftarKategori' => $this->modelKategori->getKategoriAktif(),
        ];

        return $this->render('produk/edit', $data);
    }

    /**
     * Hapus produk
     */
    public function hapus($id)
    {
        $isAjax = $this->request->isAJAX();
        $product = $this->modelProduk->find($id);
        if (!$product) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Produk tidak ditemukan.'
                ])->setStatusCode(404);
            }

            return redirect()->to('/products')->with('error', 'Produk tidak ditemukan.');
        }

        $movementCount = $this->modelMutasiStok->where('product_id', $id)->countAllResults();

        if ($movementCount > 1) {
            $message = 'Produk tidak bisa dihapus karena sudah memiliki riwayat transaksi.';

            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => $message
                ])->setStatusCode(422);
            }

            return redirect()->to('/products')->with('error', $message);
        }

        if ($this->modelProduk->delete($id)) {
            $message = 'Produk berhasil dihapus.';

            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => $message
                ]);
            }

            return redirect()->to('/products')->with('success', $message);
        }

        if ($isAjax) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Gagal menghapus produk.'
            ])->setStatusCode(500);
        }

        return redirect()->to('/products')->with('error', 'Gagal menghapus produk.');
    }

    /**
     * Ekspor daftar produk ke Excel
     */
    public function exportExcel()
    {
        $products = $this->modelProduk->getProdukDenganKategori();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Kode Barang')
            ->setCellValue('C1', 'Nama Barang')
            ->setCellValue('D1', 'Kategori')
            ->setCellValue('E1', 'Stok')
            ->setCellValue('F1', 'Satuan')
            ->setCellValue('G1', 'Harga');

        $rowNum = 2;
        foreach ($products as $idx => $row) {
            $sheet->setCellValue('A' . $rowNum, $idx + 1)
                ->setCellValue('B' . $rowNum, $row['sku'])
                ->setCellValue('C' . $rowNum, $row['name'])
                ->setCellValue('D' . $rowNum, $row['category_name'])
                ->setCellValue('E' . $rowNum, $row['current_stock'])
                ->setCellValue('F' . $rowNum, $row['unit'])
                ->setCellValue('G' . $rowNum, $row['price']);
            $rowNum++;
        }

        $fileName = 'Laporan_Stok_' . date('Ymd') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Ekspor daftar produk ke PDF
     */
    public function exportPdf()
    {
        $products = $this->modelProduk->getProdukDenganKategori();

        $html = "<h2>Laporan Inventaris Barang</h2>";
        $html .= "<table border='1' width='100%' cellpadding='5' style='border-collapse:collapse;'>
                    <thead>
                        <tr style='background:#f2f2f2;'>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Unit</th>
                        </tr>
                    </thead>
                    <tbody>";

        foreach ($products as $idx => $row) {
            $html .= "<tr>
                        <td>" . ($idx + 1) . "</td>
                        <td>{$row['sku']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['category_name']}</td>
                        <td>{$row['current_stock']}</td>
                        <td>{$row['unit']}</td>
                      </tr>";
        }
        $html .= "</tbody></table>";

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('Laporan_Stok_' . date('Ymd') . '.pdf', ["Attachment" => true]);
        exit;
    }

    /**
     * Ekspor detail satu produk sebagai PDF
     */
    public function exportSingle($id)
    {
        $product = $this->modelProduk->getProdukDenganKategoriById((int) $id);
        if (!$product) {
            return redirect()->to('/products')->with('error', 'Produk tidak ditemukan.');
        }

        $html = "<h2>Detail Produk</h2>";
        $html .= "<table border='1' width='100%' cellpadding='8' style='border-collapse:collapse;'>";
        $html .= "<tr><th align='left' width='35%'>Nama Barang</th><td>{$product['name']}</td></tr>";
        $html .= "<tr><th align='left'>Kode Barang</th><td>{$product['sku']}</td></tr>";
        $html .= "<tr><th align='left'>Kategori</th><td>{$product['category_name']}</td></tr>";
        $html .= "<tr><th align='left'>Stok</th><td>{$product['current_stock']} {$product['unit']}</td></tr>";
        $html .= "<tr><th align='left'>Harga</th><td>Rp " . number_format((float) $product['price'], 0, ',', '.') . "</td></tr>";
        $html .= "<tr><th align='left'>Deskripsi</th><td>" . ($product['description'] ?: '-') . "</td></tr>";
        $html .= "</table>";

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('Produk_' . $product['sku'] . '.pdf', ['Attachment' => true]);
        exit;
    }

    /**
     * Generate kode produk (SKU) dari kategori dan nama produk via AJAX
     */
    public function generateKodeProduk()
    {
        $categoryId  = (int) $this->request->getPost('category_id');
        $productName = trim((string) $this->request->getPost('name'));

        if ($categoryId <= 0 || $productName === '') {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Kategori dan nama produk wajib diisi.',
            ], 422);
        }

        $sku = $this->modelProduk->generateKodeProduk($categoryId, $productName);
        if ($sku === null) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Kategori tidak ditemukan.',
            ], 404);
        }

        return $this->jsonResponse([
            'status' => true,
            'sku'    => $sku,
        ]);
    }
}
