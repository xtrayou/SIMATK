<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\KodeBarangModel;

class KodeBarangController extends ResourceController
{
    /**
     * Get all item codes
     */
    public function index()
    {
        $model = new KodeBarangModel();
        $data = $model->getAll();
        
        return $this->respond($data);
    }
}
