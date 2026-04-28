<?php

namespace App\Models\Permintaan;

use CodeIgniter\Model;

/**
 * PermintaanModel - Model untuk mengelola data permintaan ATK
 *
 * Relasi:
 * - Permintaan terkait Barang (melalui request_items)
 * - Permintaan terkait Pengguna (borrower)
 */
class PermintaanModel extends Model
{
    protected $table = 'requests';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'borrower_name',
        'borrower_id_number',
        'borrower_unit',
        'email',
        'receipt_code',
        'request_date',
        'due_date',
        'status',
        'status_reason',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil detail permintaan beserta daftar item-nya
     *
     * @param int $id ID permintaan
     * @return array|null Data permintaan lengkap dengan item atau null jika tidak ditemukan
     */
    public function getPermintaanDenganItem(int $id): ?array
    {
        $permintaan = $this->find($id);
        if (!$permintaan) {
            return null;
        }

        $modelItemPermintaan = new ItemPermintaanModel();
        $permintaan['items'] = $modelItemPermintaan->select('request_items.*, barang.name as product_name, barang.sku as product_sku, barang.unit')
            ->join('barang', 'barang.id = request_items.product_id')
            ->where('request_items.request_id', $id)
            ->findAll();

        return $permintaan;
    }
}
