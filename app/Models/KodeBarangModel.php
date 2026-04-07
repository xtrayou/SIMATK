<?php

namespace App\Models;

use CodeIgniter\Model;

class KodeBarangModel extends Model
{
    protected $table            = 'kode_barang';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['kode', 'nama'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    /**
     * Get all item codes, ordered by code
     */
    public function getAll()
    {
        return $this->orderBy('kode', 'ASC')->findAll();
    }
}
