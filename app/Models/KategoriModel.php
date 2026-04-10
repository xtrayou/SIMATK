<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * KategoriModel - Model untuk mengelola data kategori produk
 *
 * Relasi:
 * - Kategori memiliki banyak Produk (categories.id → products.category_id)
 */
class KategoriModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'description',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil kategori yang aktif
     *
     * @return array Daftar kategori aktif
     */
    public function getKategoriAktif(): array
    {
        return $this->where('is_active', true)
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    /**
     * Ambil kategori beserta jumlah produk di setiap kategori
     *
     * @param string      $kataKunci Kata kunci pencarian
     * @param mixed       $status    Filter status aktif/nonaktif
     * @param string      $kolomUrut Kolom untuk pengurutan
     * @param string      $arahUrut  Arah urutan (ASC/DESC)
     * @param int         $batas     Batas jumlah data per halaman
     * @param int         $offset    Offset data
     * @return array Daftar kategori dengan jumlah produk
     */
    public function getKategoriDenganJumlahProduk(
        string $kataKunci = '',
        $status = null,
        string $kolomUrut = 'name',
        string $arahUrut = 'ASC',
        int $batas = 0,
        int $offset = 0
    ): array {
        $builder = $this->select('categories.*, COUNT(products.id) as product_count')
            ->join('products', 'products.category_id = categories.id', 'left')
            ->groupBy('categories.id');

        if ($kataKunci) {
            $builder->like('categories.name', $kataKunci)
                ->orLike('categories.description', $kataKunci);
        }

        if ($status !== null && $status !== '') {
            $builder->where('categories.is_active', $status);
        }

        $builder->orderBy('categories.' . $kolomUrut, $arahUrut);

        if ($batas > 0) {
            return $builder->findAll($batas, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Hitung jumlah kategori berdasarkan filter
     *
     * @param string $kataKunci Kata kunci pencarian
     * @param mixed  $status    Filter status
     * @return int Jumlah kategori
     */
    public function hitungKategori(string $kataKunci = '', $status = null): int
    {
        $builder = $this;

        if ($kataKunci) {
            $builder->like('name', $kataKunci)
                ->orLike('description', $kataKunci);
        }

        if ($status !== null && $status !== '') {
            $builder->where('is_active', $status);
        }

        return $builder->countAllResults();
    }

    /**
     * Cek apakah kategori bisa dihapus (tidak punya produk terkait)
     *
     * @param int $id ID kategori
     * @return bool True jika bisa dihapus
     */
    public function bisaDihapus(int $id): bool
    {
        $modelProduk = new ProdukModel();
        $jumlahProduk = $modelProduk->where('category_id', $id)->countAllResults();

        return $jumlahProduk === 0;
    }
}
