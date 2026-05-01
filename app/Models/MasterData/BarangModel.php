<?php

namespace App\Models\MasterData;

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
    protected $table = 'barang';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'sku',
        'category_id',
        'description',
        'price',
        'min_stock',
        'current_stock',
        'stock_baik',
        'stock_rusak',
        'unit',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

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
        return $this->select('barang.*, categories.name as category_name, (barang.current_stock * barang.price) as total_value')
            ->join('categories', 'categories.id = barang.category_id')
            ->where('barang.is_active', true)
            ->where('barang.current_stock >', 0)
            ->orderBy('total_value', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    // ===== BUSINESS LOGIC =====

    /**
     * Menentukan kode barang (SKU) yang valid.
     * Mengembalikan SKU yang sudah disanitasi.
     *
     * @param string $requestedSku SKU yang diminta
     * @param int    $categoryId   ID Kategori (untuk referensi)
     * @return string SKU yang telah divalidasi
     */
    public function resolveSku(string $requestedSku, int $categoryId, int $excludeId = 0): string
    {
        $requestedSku = trim($requestedSku);
        if ($requestedSku === '') {
            return '';
        }

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
                    barang.id, 
                    barang.name, 
                    barang.sku, 
                    barang.category_id, 
                    barang.description, 
                    barang.price, 
                    barang.min_stock, 
                    barang.current_stock, 
                    barang.stock_baik,
                    barang.stock_rusak,
                    barang.unit, 
                    barang.is_active,
                    categories.name as category_name,
                    CASE 
                        WHEN barang.current_stock = 0 THEN 'habis'
                        WHEN barang.current_stock <= barang.min_stock THEN 'rendah'
                        ELSE 'normal'
                    END as stock_status
                ")
            ->join('categories', 'categories.id = barang.category_id')
            ->where('barang.is_active', true);

        if (!empty($filter['search'])) {
            $builder->groupStart()
                ->like('barang.name', $filter['search'])
                ->orLike('barang.sku', $filter['search'])
                ->orLike('barang.description', $filter['search'])
                ->groupEnd();
        }

        if (!empty($filter['category'])) {
            $builder->where('barang.category_id', $filter['category']);
        }

        if (!empty($filter['stock_status'])) {
            switch ($filter['stock_status']) {
                case 'habis':
                    $builder->where('barang.current_stock', 0);
                    break;
                case 'rendah':
                    $builder->where('barang.current_stock <= barang.min_stock', null, false)
                        ->where('barang.current_stock >', 0);
                    break;
                case 'normal':
                    $builder->where('barang.current_stock > barang.min_stock', null, false);
                    break;
            }
        }

        return $builder->orderBy('barang.name', 'ASC')->findAll();
    }

    /**
     * Ambil semua barang beserta nama kategori
     *
     * @return array Daftar barang dengan kategori
     */
    public function getBarangDenganKategori(): array
    {
        return $this->select('barang.id, barang.name, barang.sku, barang.category_id, barang.current_stock, barang.unit, categories.name as category_name')
            ->join('categories', 'categories.id = barang.category_id')
            ->where('barang.is_active', true)
            ->orderBy('barang.name', 'ASC')
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
        return $this->select('barang.id, barang.name, barang.sku, barang.category_id, barang.description, barang.price, barang.min_stock, barang.current_stock, barang.stock_baik, barang.stock_rusak, barang.unit, barang.is_active, categories.name as category_name')
            ->join('categories', 'categories.id = barang.category_id')
            ->where('barang.id', $id)
            ->first();
    }

    /**
     * Hitung total nilai inventaris (stok × harga modal)
     *
     * @return float Total nilai inventaris
     */
    public function getTotalNilaiInventaris(): float
    {
        $result = $this->select('SUM(current_stock * price) as total_value', false)
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
        $builder = $this->select('barang.id, barang.name, barang.sku, barang.current_stock, barang.stock_baik, barang.min_stock, barang.unit, categories.name as category_name, IFNULL(barang.stock_baik, barang.current_stock) as available_stock', false)
            ->join('categories', 'categories.id = barang.category_id')
            ->where('IFNULL(barang.stock_baik, barang.current_stock) <= barang.min_stock', null, false)
            ->where('IFNULL(barang.stock_baik, barang.current_stock) > 0', null, false)
            ->where('barang.is_active', true)
            ->orderBy('IFNULL(barang.stock_baik, barang.current_stock)', 'ASC', false);

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

        return $prefix . $namePart . str_pad((string) $number, 4, '0', STR_PAD_LEFT);
    }
}
