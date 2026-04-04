<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\ProdukModel;
use App\Models\KategoriModel;
use App\Models\MutasiStokModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Exception;

class ProdukController extends BaseController
{
    protected ProdukModel $modelProduk;
    protected KategoriModel $modelKategori;
    protected MutasiStokModel $modelMutasiStok;

    public function __construct()
    {
        $this->modelProduk = new ProdukModel();
        $this->modelKategori = new KategoriModel();
        $this->modelMutasiStok = new MutasiStokModel();
    }

    /**
     * Show product list
     */
    public function index()
    {
        $this->setPageData('Daftar Produk', 'Manajemen stok dan inventaris barang');

        $search      = $this->request->getGet('search');
        $category    = $this->request->getGet('category');
        $stockStatus = $this->request->getGet('stock_status');

        $products = $this->modelProduk->getFilteredProducts([
            'search'       => $search,
            'category'     => $category,
            'stock_status' => $stockStatus
        ]);

        $categories = $this->modelKategori->getActiveCategories();

        $data = [
            'daftarProduk'   => $products,
            'daftarKategori' => $categories,
            'filterCari'     => $search,
            'filterKategori' => $category,
            'filterStok'     => $stockStatus,
            'totalItem'      => count($products)
        ];

        return $this->render('products/index', $data);
    }

    /**
     * Create product form
     */
    public function create()
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
            'daftarKategori' => $this->modelKategori->getActiveCategories(),
        ];

        return $this->render('products/create', $data);
    }

    /**
     * Store or update product
     */
    public function save()
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
            // This matches the "Tampilkan Pesan Error" flow
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Mohon periksa kembali data yang Anda masukkan.');
        }

        $payload = [
            'name'          => $this->request->getPost('name'),
            'sku'           => strtoupper((string)$this->request->getPost('sku')),
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
     * Product details
     */
    public function show($id)
    {
        $product = $this->modelProduk->getProductWithCategory((int)$id);

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

        return $this->render('products/show', $data);
    }

    /**
     * Edit product form
     */
    public function edit($id)
    {
        $product = $this->modelProduk->find($id);

        if (!$product) {
            return redirect()->to('/products')->with('error', 'Produk tidak ditemukan.');
        }

        $this->setPageData('Edit Produk', $product['name']);

        $data = [
            'produk'         => $product,
            'daftarKategori' => $this->modelKategori->getActiveCategories(),
        ];

        return $this->render('products/edit', $data);
    }

    /**
     * Delete product
     */
    public function delete($id)
    {
        $product = $this->modelProduk->find($id);
        if (!$product) {
            return redirect()->to('/products')->with('error', 'Produk tidak ditemukan.');
        }

        $movementCount = $this->modelMutasiStok->where('product_id', $id)->countAllResults();

        if ($movementCount > 1) { // Allow deletion if only initial stock movement exists
            return redirect()->to('/products')->with('error', 'Produk tidak bisa dihapus karena sudah memiliki riwayat transaksi.');
        }

        if ($this->modelProduk->delete($id)) {
            return redirect()->to('/products')->with('success', 'Produk berhasil dihapus.');
        }

        return redirect()->to('/products')->with('error', 'Gagal menghapus produk.');
    }

    /**
     * Export to Excel
     */
    public function exportExcel()
    {
        $products = $this->modelProduk->getProductsWithCategory();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No')
            ->setCellValue('B1', 'SKU')
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
     * Export to PDF
     */
    public function exportPdf()
    {
        $products = $this->modelProduk->getProductsWithCategory();

        $html = "<h2>Laporan Inventaris Barang</h2>";
        $html .= "<table border='1' width='100%' cellpadding='5' style='border-collapse:collapse;'>
                    <thead>
                        <tr style='background:#f2f2f2;'>
                            <th>No</th>
                            <th>SKU</th>
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
     * Export single product detail as PDF
     */
    public function exportSingle($id)
    {
        $product = $this->modelProduk->getProductWithCategory((int) $id);
        if (!$product) {
            return redirect()->to('/products')->with('error', 'Produk tidak ditemukan.');
        }

        $html = "<h2>Detail Produk</h2>";
        $html .= "<table border='1' width='100%' cellpadding='8' style='border-collapse:collapse;'>";
        $html .= "<tr><th align='left' width='35%'>Nama Barang</th><td>{$product['name']}</td></tr>";
        $html .= "<tr><th align='left'>SKU</th><td>{$product['sku']}</td></tr>";
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
     * Generate SKU from category and product name via AJAX
     */
    public function generateSKU()
    {
        $categoryId  = (int) $this->request->getPost('category_id');
        $productName = trim((string) $this->request->getPost('name'));

        if ($categoryId <= 0 || $productName === '') {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Kategori dan nama produk wajib diisi.',
            ], 422);
        }

        $sku = $this->modelProduk->generateSKU($categoryId, $productName);
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
