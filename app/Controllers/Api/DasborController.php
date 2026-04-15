<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\KategoriModel;
use App\Models\BarangModel;

class DasborController extends BaseController
{
    protected BarangModel $modelBarang;
    protected KategoriModel $modelKategori;

    public function __construct()
    {
        $this->modelBarang = new BarangModel();
        $this->modelKategori = new KategoriModel();
    }

    /**
     * Get dashboard statistics via AJAX
     */
    public function getStats()
    {
        try {
            $stats = [
                'total_products'   => $this->modelBarang->where('is_active', true)->countAllResults(),
                'total_categories' => $this->modelKategori->where('is_active', true)->countAllResults(),
                'low_stock_count'  => count($this->modelBarang->getBarangStokRendah()),
            ];

            return $this->jsonResponse([
                'status' => true,
                'stats'  => $stats
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
