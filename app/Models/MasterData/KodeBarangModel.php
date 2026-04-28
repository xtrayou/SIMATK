<?php

namespace App\Models\MasterData;

use CodeIgniter\Model;

class KodeBarangModel extends Model
{
    protected $table = 'kode_barang';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'kode',
        'nama',
    ];

    protected $useTimestamps = true;

    /**
     * Get all item codes
     */
    public function getAll(): array
    {
        return $this->orderBy('kode', 'ASC')->findAll();
    }

    /**
     * Search kode barang by keyword (kode or nama)
     */
    public function cariKodeBarang(string $keyword = ''): array
    {
        if ($keyword === '') {
            return $this->getAll();
        }

        return $this->groupStart()
            ->like('kode', $keyword)
            ->orLike('nama', $keyword)
            ->groupEnd()
            ->orderBy('kode', 'ASC')
            ->findAll();
    }
}
