<?php

namespace App\Models\MasterData;

use CodeIgniter\Model;

/**
 * KategoriModel - Model untuk mengelola data kategori barang
 *
 * Relasi:
 * - Kategori memiliki banyak Barang (categories.id → products.category_id)
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

    // ===== BASIC =====

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
     * Hitung jumlah kategori aktif.
     *
     * @return int
     */
    public function countAktif(): int
    {
        return $this->where('is_active', true)->countAllResults();
    }

    // ===== DASHBOARD =====

    /**
     * Ambil distribusi kategori untuk dashboard.
     *
     * @return array
     */
    public function getDistribusiUntukDashboard(): array
    {
        return $this->select('categories.name, COUNT(barang.id) as product_count, SUM(barang.current_stock * barang.price) as total_value')
            ->join('barang', 'barang.category_id = categories.id', 'left')
            ->where('categories.is_active', true)
            ->groupBy('categories.id')
            ->orderBy('product_count', 'DESC')
            ->findAll();
    }

    // ===== FILTER =====

    /**
     * Ambil kategori beserta jumlah barang di setiap kategori
     *
     * @param string      $kataKunci Kata kunci pencarian
     * @param mixed       $status    Filter status aktif/nonaktif
     * @param string      $kolomUrut Kolom untuk pengurutan
     * @param string      $arahUrut  Arah urutan (ASC/DESC)
     * @param int         $batas     Batas jumlah data per halaman
     * @param int         $offset    Offset data
     * @return array Daftar kategori dengan jumlah barang
     */
    public function getKategoriDenganJumlahBarang(
        string $kataKunci = '',
        $status = null,
        string $kolomUrut = 'name',
        string $arahUrut = 'ASC',
        int $batas = 0,
        int $offset = 0
    ): array {
        $builder = $this->select('categories.*, COUNT(barang.id) as jumlah_barang')
            ->join('barang', 'barang.category_id = categories.id', 'left')
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

    // ===== VALIDATION =====

    /**
     * Cek apakah kategori bisa dihapus (tidak punya barang terkait)
     *
     * @param int $id ID kategori
     * @return bool True jika bisa dihapus
     */
    public function bisaDihapus(int $id): bool
    {
        $modelBarang = new BarangModel();
        $jumlahBarang = $modelBarang->where('category_id', $id)->countAllResults();

        return $jumlahBarang === 0;
    }
}
