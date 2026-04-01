<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\KategoriModel;

class KategoriController extends BaseController
{
    protected KategoriModel $modelKategori;

    public function __construct()
    {
        $this->modelKategori = new KategoriModel();
    }

    /**
     * GET /api/categories/active
     */
    public function getActive()
    {
        return $this->jsonResponse([
            'status' => true,
            'data'   => $this->modelKategori->getActiveCategories(),
        ]);
    }
}

