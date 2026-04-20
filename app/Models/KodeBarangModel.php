<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * KodeBarangModel - Referensi kode barang
 *
 * Setelah refactor, data kode barang dibaca langsung dari tabel products (SKU + Name).
 * Tabel kode_barang sudah dihapus untuk menghilangkan duplikasi.
 */
class KodeBarangModel extends Model
{
    protected $table            = 'barang';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get all item codes (SKU + name) from products, ordered by SKU
     */
    public function getAll(): array
    {
        return $this->select('id, sku AS kode, name AS nama')
            ->where('is_active', true)
            ->orderBy('sku', 'ASC')
            ->findAll();
    }

    /**
     * Search kode barang by keyword (kode or nama)
     */
    public function cariKodeBarang(string $keyword = ''): array
    {
        $builder = $this->select('id, sku AS kode, name AS nama')
            ->where('is_active', true);

        if ($keyword !== '') {
            $builder->groupStart()
                ->like('sku', $keyword)
                ->orLike('name', $keyword)
                ->groupEnd();
        }

        return $builder->orderBy('sku', 'ASC')->findAll();
    }
}
