<?php

namespace App\Models\Permintaan;

use CodeIgniter\Model;

class ItemPermintaanModel extends Model
{
    protected $table = 'request_items';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'request_id',
        'product_id',
        'quantity',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
