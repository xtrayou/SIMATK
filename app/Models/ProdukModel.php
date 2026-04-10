<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ProdukModel - Model untuk mengelola data produk/barang
 *
 * Relasi:
 * - Produk memiliki Kategori (category_id → categories.id)
 * - PergerakanStok terkait Produk (stock_movements.product_id → products.id)
 */
class ProdukModel extends Model
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

    /**
     * Ambil produk yang terfilter beserta informasi kategori
     *
     * @param array $filter Filter berupa search, category, stock_status
     * @return array Daftar produk terfilter
     */
    public function getProdukTerfilter(array $filter = []): array
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
     * Ambil semua produk beserta nama kategori
     *
     * @return array Daftar produk dengan kategori
     */
    public function getProdukDenganKategori(): array
    {
        return $this->select('products.id, products.name, products.sku, products.category_id, products.current_stock, products.unit, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id')
            ->where('products.is_active', true)
            ->orderBy('products.name', 'ASC')
            ->findAll();
    }

    /**
     * Ambil satu produk beserta nama kategori berdasarkan ID
     *
     * @param int $id ID produk
     * @return array|null Data produk atau null jika tidak ditemukan
     */
    public function getProdukDenganKategoriById(int $id): ?array
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
     * Ambil produk dengan stok rendah
     *
     * @param int $limit Batas jumlah data (0 = semua)
     * @return array Daftar produk stok rendah
     */
    public function getProdukStokRendah(int $limit = 0): array
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

    /**
     * Generate kode produk otomatis berdasarkan kategori dan nama produk
     *
     * @param int    $idKategori  ID kategori
     * @param string $namaProduk  Nama produk
     * @return string|null Kode produk yang digenerate atau null jika kategori tidak ditemukan
     */
    public function generateKodeProduk(int $idKategori, string $namaProduk): ?string
    {
        $modelKategori = new KategoriModel();
        $kategori = $modelKategori->find($idKategori);
        if (!$kategori) {
            return null;
        }

        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $kategori['name'] ?? ''), 0, 3));
        $namePart = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $namaProduk), 0, 3));

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
