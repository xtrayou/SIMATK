<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * BarangModel - Model untuk mengelola data barang/barang
 *
 * Relasi:
 * - Barang memiliki Kategori (category_id → categories.id)
 * - PergerakanStok terkait Barang (stock_movements.product_id → products.id)
 */
class BarangModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'sku',
        'category_id',
        'description',
        'price',
        'cost_price',
        'min_stock',
        'current_stock',
        'stock_baik',
        'stock_rusak',
        'unit',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ===== BASIC QUERY =====

    /**
     * Ambil daftar barang aktif untuk landing page.
     *
     * @return array
     */
    public function getBarangAktif(): array
    {
        return $this->select('id, name, category_id, current_stock, unit')
            ->where('is_active', true)
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    /**
     * Hitung jumlah barang aktif.
     *
     * @return int
     */
    public function countAktif(): int
    {
        return $this->where('is_active', true)->countAllResults();
    }

    /**
     * Hitung jumlah barang dengan stok habis.
     */
    public function countStokHabis(): int
    {
        return $this->where('IFNULL(stock_baik, current_stock) <= 0', null, false)
            ->where('is_active', true)
            ->countAllResults();
    }

    /**
     * Hitung jumlah barang dengan stok rendah.
     */
    public function countStokRendah(): int
    {
        return $this->where('IFNULL(stock_baik, current_stock) <= min_stock', null, false)
            ->where('IFNULL(stock_baik, current_stock) > 0', null, false)
            ->where('is_active', true)
            ->countAllResults();
    }

    /**
     * Hitung jumlah barang dengan stok normal.
     */
    public function countStokNormal(): int
    {
        return $this->where('IFNULL(stock_baik, current_stock) > min_stock', null, false)
            ->where('is_active', true)
            ->countAllResults();
    }

    /**
     * Ambil barang teratas berdasarkan nilai finansial.
     *
     * @param int $limit
     * @return array
     */
    public function getTopProductsByValue(int $limit = 5): array
    {
        return $this->select('products.*, categories.name as category_name, (products.current_stock * products.price) as total_value')
            ->join('categories', 'categories.id = products.category_id')
            ->where('products.is_active', true)
            ->where('products.current_stock >', 0)
            ->orderBy('total_value', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    // ===== BUSINESS LOGIC =====

    /**
     * Menentukan kode barang (SKU) yang valid.
     * Jika kode yang diminta tidak ada di referensi, gunakan kode "lainnya" (akhiran 999)
     * dari kategori yang dipilih.
     *
     * @param string $requestedSku SKU yang diminta
     * @param int    $categoryId   ID Kategori
     * @return string SKU yang telah divalidasi
     */
    public function resolveSku(string $requestedSku, int $categoryId): string
    {
        $modelKodeBarang = new KodeBarangModel();
        $modelKategori = new KategoriModel();

        $requestedSku = preg_replace('/\D+/', '', $requestedSku) ?? '';
        if ($requestedSku === '') {
            return '';
        }

        // Cek apakah SKU yang diminta ada persis di tabel referensi
        $exactKode = $modelKodeBarang->where('kode', $requestedSku)->first();
        if ($exactKode) {
            return $requestedSku;
        }

        // Jika tidak ada, coba cari kode cadangan berdasarkan kategori
        if ($categoryId <= 0) {
            return $requestedSku; // Tidak bisa lanjut tanpa kategori
        }

        $category = $modelKategori->find($categoryId);
        if (!$category || empty($category['name'])) {
            return $requestedSku; // Kategori tidak valid
        }

        $categoryName = trim((string) $category['name']);
        if ($categoryName === '') {
            return $requestedSku;
        }

        // Cari kode yang cocok dengan nama kategori
        $categoryKode = $modelKodeBarang
            ->where('LOWER(nama)', strtolower($categoryName))
            ->first();

        // Jika tidak ketemu persis, cari yang mirip
        if (!$categoryKode) {
            $categoryKode = $modelKodeBarang
                ->like('nama', $categoryName)
                ->orderBy('kode', 'ASC')
                ->first();
        }

        $baseCode = (string) ($categoryKode['kode'] ?? '');
        $baseCode = preg_replace('/\D+/', '', $baseCode) ?? '';

        // Jika kode dasar ditemukan, gunakan format kode cadangan (xxx999)
        if (strlen($baseCode) >= 10) {
            return substr($baseCode, 0, 7) . '999';
        }

        // Jika semua gagal, kembalikan SKU asli yang diminta
        return $requestedSku;
    }

    // ===== FILTER / LISTING QUERY =====

    /**
     * Ambil barang yang terfilter beserta informasi kategori
     *
     * @param array $filter Filter berupa search, category, stock_status
     * @return array Daftar barang terfilter
     */
    public function getBarangTerfilter(array $filter = []): array
    {
        $builder = $this->select("
                    products.id, 
                    products.name, 
                    products.sku, 
                    products.category_id, 
                    products.description, 
                    products.price, 
                    products.cost_price, 
                    products.min_stock, 
                    products.current_stock, 
                    products.stock_baik,
                    products.stock_rusak,
                    products.unit, 
                    products.is_active,
                    categories.name as category_name,
                    CASE 
                        WHEN products.current_stock = 0 THEN 'habis'
                        WHEN products.current_stock <= products.min_stock THEN 'rendah'
                        ELSE 'normal'
                    END as stock_status
                ")
            ->join('categories', 'categories.id = products.category_id')
            ->where('products.is_active', true);

        if (!empty($filter['search'])) {
            $builder->groupStart()
                ->like('products.name', $filter['search'])
                ->orLike('products.sku', $filter['search'])
                ->orLike('products.description', $filter['search'])
                ->groupEnd();
        }

        if (!empty($filter['category'])) {
            $builder->where('products.category_id', $filter['category']);
        }

        if (!empty($filter['stock_status'])) {
            switch ($filter['stock_status']) {
                case 'habis':
                    $builder->where('products.current_stock', 0);
                    break;
                case 'rendah':
                    $builder->where('products.current_stock <= products.min_stock', null, false)
                        ->where('products.current_stock >', 0);
                    break;
                case 'normal':
                    $builder->where('products.current_stock > products.min_stock', null, false);
                    break;
            }
        }

        return $builder->orderBy('products.name', 'ASC')->findAll();
    }

    /**
     * Ambil semua barang beserta nama kategori
     *
     * @return array Daftar barang dengan kategori
     */
    public function getBarangDenganKategori(): array
    {
        return $this->select('products.id, products.name, products.sku, products.category_id, products.current_stock, products.unit, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id')
            ->where('products.is_active', true)
            ->orderBy('products.name', 'ASC')
            ->findAll();
    }

    /**
     * Ambil satu barang beserta nama kategori berdasarkan ID
     *
     * @param int $id ID barang
     * @return array|null Data barang atau null jika tidak ditemukan
     */
    public function getBarangDenganKategoriById(int $id): ?array
    {
        return $this->select('products.id, products.name, products.sku, products.category_id, products.description, products.price, products.cost_price, products.min_stock, products.current_stock, products.stock_baik, products.stock_rusak, products.unit, products.is_active, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id')
            ->where('products.id', $id)
            ->first();
    }

    /**
     * Hitung total nilai inventaris (stok × harga modal)
     *
     * @return float Total nilai inventaris
     */
    public function getTotalNilaiInventaris(): float
    {
        $result = $this->select('SUM(current_stock * cost_price) as total_value', false)
            ->where('is_active', true)
            ->first();

        return (float) ($result['total_value'] ?? 0);
    }

    /**
     * Ambil barang dengan stok rendah
     *
     * @param int $limit Batas jumlah data (0 = semua)
     * @return array Daftar barang stok rendah
     */
    public function getBarangStokRendah(int $limit = 0): array
    {
        $builder = $this->select('products.id, products.name, products.sku, products.current_stock, products.stock_baik, products.min_stock, products.unit, categories.name as category_name, IFNULL(products.stock_baik, products.current_stock) as available_stock', false)
            ->join('categories', 'categories.id = products.category_id')
            ->where('IFNULL(products.stock_baik, products.current_stock) <= products.min_stock', null, false)
            ->where('IFNULL(products.stock_baik, products.current_stock) > 0', null, false)
            ->where('products.is_active', true)
            ->orderBy('IFNULL(products.stock_baik, products.current_stock)', 'ASC', false);

        if ($limit > 0) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    // ===== SKU GENERATION =====

    /**
     * Generate kode barang otomatis berdasarkan kategori dan nama barang
     *
     * @param int    $idKategori  ID kategori
     * @param string $namaBarang  Nama barang
     * @return string|null Kode barang yang digenerate atau null jika kategori tidak ditemukan
     */
    public function generateKodeBarang(int $idKategori, string $namaBarang): ?string
    {
        $modelKategori = new KategoriModel();
        $kategori = $modelKategori->find($idKategori);
        if (!$kategori) {
            return null;
        }

        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $kategori['name'] ?? ''), 0, 3));
        $namePart = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $namaBarang), 0, 3));

        $lastProduct = $this->like('sku', $prefix . $namePart, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        $number = 1;
        if ($lastProduct) {
            $lastNumber = (int) substr($lastProduct['sku'], strlen($prefix . $namePart));
            $number = $lastNumber + 1;
        }

        return $prefix . $namePart . str_pad((string)$number, 4, '0', STR_PAD_LEFT);
    }
}
