<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;

use App\Models\MutasiStokModel;
use App\Services\BarangExportService;

/**
 * BarangController - Controller untuk mengelola barang/barang
 *
 * Relasi:
 * - Barang memiliki Kategori
 * - PergerakanStok terkait Barang
 */
class BarangController extends BaseController
{
    protected BarangModel $modelBarang;
    protected KategoriModel $modelKategori;

    protected MutasiStokModel $modelMutasiStok;
    protected BarangExportService $exportService;

    public function __construct()
    {
        $this->modelBarang = new BarangModel();
        $this->modelKategori = new KategoriModel();

        $this->modelMutasiStok = new MutasiStokModel();
        $this->exportService = new BarangExportService();
    }

    /**
     * Tampilkan daftar barang
     */
    public function index()
    {
        $this->setPageData('Daftar Barang', 'Manajemen stok dan inventaris barang');

        $search      = $this->request->getGet('search');
        $category    = $this->request->getGet('category');
        $stockStatus = $this->request->getGet('stock_status');

        $products = $this->modelBarang->getBarangTerfilter([
            'search'       => $search,
            'category'     => $category,
            'stock_status' => $stockStatus
        ]);

        $categories = $this->modelKategori->getKategoriAktif();

        $data = [
            'daftarBarang'   => $products,
            'daftarKategori' => $categories,
            'filterCari'     => $search,
            'filterKategori' => $category,
            'filterStok'     => $stockStatus,
            'totalItem'      => count($products)
        ];

        return $this->render('barang/index', $data);
    }

    /**
     * Form tambah barang baru
     */
    public function tambah()
    {
        $this->setPageData('Tambah Barang', 'Input data barang baru ke sistem');

        $data = [
            'barang' => [
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

        return $this->render('barang/create', $data);
    }

    /**
     * Simpan barang baru atau perbarui barang yang sudah ada
     */
    public function simpan()
    {
        $productId = (int) ($this->request->getPost('id') ?? 0);
        $isUpdate = $productId > 0;

        $rules = [
            'name'          => 'required|min_length[3]|max_length[255]',
            'sku'           => "required|min_length[3]|max_length[50]",
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

        $data = $this->request->getPost();
        $resolvedSku = $this->modelBarang->resolveSku(
            (string) ($data['sku'] ?? ''),
            (int) ($data['category_id'] ?? 0)
        );

        $duplicateSku = $this->modelBarang->where('sku', $resolvedSku)->first();
        if ($duplicateSku && (!$isUpdate || (int) $duplicateSku['id'] !== $productId)) {
            return redirect()->back()->withInput()->with('error', 'Kode barang ' . $resolvedSku . ' sudah digunakan. Silakan gunakan kode lain.');
        }

        $data['sku'] = $resolvedSku;

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            if ($isUpdate) {
                $this->modelBarang->update($productId, $data);
            } else {
                $initialStock = (int) ($data['initial_stock'] ?? 0);
                $data['current_stock'] = $initialStock;
                $data['stock_baik'] = $initialStock;
                $data['stock_rusak'] = 0;

                $savedProductId = (int) $this->modelBarang->insert($data, true);
                if ($savedProductId <= 0) {
                    throw new \Exception('Gagal menyimpan data barang ke database.');
                }

                if ($initialStock > 0) {
                    $this->modelMutasiStok->insert([
                        'product_id'   => $savedProductId,
                        'type'         => 'IN',
                        'quantity'     => $initialStock,
                        'notes'        => 'Stok awal barang baru',
                        'reference_no' => 'INIT-' . time(),
                        'created_by'   => session('userId') ?: null,
                    ]);
                }
            }

            $db->transComplete();
            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menyimpan data barang ke database.');
            }

            $message = $isUpdate ? 'Barang berhasil diperbarui.' : 'Barang berhasil ditambahkan.';
            return redirect()->to('/products')->with('success', $message);
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Tampilkan detail barang
     */
    public function detail($id)
    {
        $barang = $this->modelBarang->getBarangDenganKategoriById((int)$id);

        if (!$barang) {
            return redirect()->to('/products')->with('error', 'Barang tidak ditemukan.');
        }

        $this->setPageData('Detail Barang', $barang['name']);

        $stockHistory = $this->modelMutasiStok->where('product_id', $id)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->findAll();

        $stats = [
            'total_masuk'  => $this->modelMutasiStok->where('product_id', $id)->where('type', 'IN')->selectSum('quantity', 'total')->first()['total'] ?? 0,
            'total_keluar' => $this->modelMutasiStok->where('product_id', $id)->where('type', 'OUT')->selectSum('quantity', 'total')->first()['total'] ?? 0,
        ];

        $currentStock = (int) ($barang['current_stock'] ?? 0);
        $minStock = (int) ($barang['min_stock'] ?? 0);
        $statusClass = $currentStock <= $minStock ? 'text-danger' : 'text-success';

        $margin = null;
        $price = (float) ($barang['price'] ?? 0);
        $costPrice = (float) ($barang['cost_price'] ?? 0);
        if ($price > 0) {
            $margin = (($price - $costPrice) / $price) * 100;
        }

        $data = [
            'barang'      => $barang,
            'riwayatStok' => $stockHistory,
            'statistik'   => $stats,
            'statusClass' => $statusClass,
            'netMutasi' => (int) $stats['total_masuk'] - (int) $stats['total_keluar'],
            'margin' => $margin,
        ];

        return $this->render('barang/show', $data);
    }

    /**
     * Form ubah/edit barang
     */
    public function ubah($id)
    {
        $product = $this->modelBarang->find($id);

        if (!$product) {
            return redirect()->to('/products')->with('error', 'Barang tidak ditemukan.');
        }

        $this->setPageData('Edit Barang', $product['name']);

        $data = [
            'barang'         => $product,
            'daftarKategori' => $this->modelKategori->getKategoriAktif(),
        ];

        return $this->render('barang/edit', $data);
    }

    /**
     * Hapus barang
     */
    public function hapus($id)
    {
        $isAjax = $this->request->isAJAX();
        $product = $this->modelBarang->find($id);
        if (!$product) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Barang tidak ditemukan.'
                ])->setStatusCode(404);
            }

            return redirect()->to('/products')->with('error', 'Barang tidak ditemukan.');
        }

        $movementCount = $this->modelMutasiStok->where('product_id', $id)->countAllResults();

        if ($movementCount > 1) {
            $message = 'Barang tidak bisa dihapus karena sudah memiliki riwayat transaksi.';

            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => $message
                ])->setStatusCode(422);
            }

            return redirect()->to('/products')->with('error', $message);
        }

        if ($this->modelBarang->delete($id)) {
            $message = 'Barang berhasil dihapus.';

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
                'message' => 'Gagal menghapus barang.'
            ])->setStatusCode(500);
        }

        return redirect()->to('/products')->with('error', 'Gagal menghapus barang.');
    }

    /**
     * Ekspor daftar barang ke Excel
     */
    public function exportExcel()
    {
        $products = $this->modelBarang->getBarangDenganKategori();
        $this->exportService->exportExcel($products);
    }

    /**
     * Ekspor daftar barang ke PDF
     */
    public function exportPdf()
    {
        $products = $this->modelBarang->getBarangDenganKategori();
        $this->exportService->exportPdf($products);
    }

    /**
     * Ekspor detail satu barang sebagai PDF
     */
    public function exportSingle($id)
    {
        $product = $this->modelBarang->getBarangDenganKategoriById((int) $id);
        if (!$product) {
            return redirect()->to('/products')->with('error', 'Barang tidak ditemukan.');
        }
        $this->exportService->exportSingle($product);
    }

    /**
     * Generate kode barang (SKU) dari kategori dan nama barang via AJAX
     */
    public function generateKodeBarang()
    {
        $categoryId  = (int) $this->request->getPost('category_id');
        $productName = trim((string) $this->request->getPost('name'));

        if ($categoryId <= 0 || $productName === '') {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Kategori dan nama barang wajib diisi.',
            ], 422);
        }

        $sku = $this->modelBarang->generateKodeBarang($categoryId, $productName);
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
