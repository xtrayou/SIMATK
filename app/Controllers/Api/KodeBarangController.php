<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MasterData\KodeBarangModel;

class KodeBarangController extends ResourceController
{
    /**
     * Get all item codes (reads from products table after refactor)
     */
    public function index()
    {
        $model = new KodeBarangModel();
        $data = $model->getAll();
        
        return $this->respond($data);
    }
}
