<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ProdukModel;

class ProdukController extends BaseController
{
    protected ProdukModel $modelProduk;

    public function __construct()
    {
        $this->modelProduk = new ProdukModel();
    }

    /**
     * GET /api/products/search?q=...
     */
    public function search()
    {
        $keyword = trim((string) ($this->request->getGet('q') ?? ''));
        $limit   = (int) ($this->request->getGet('limit') ?? 10);
        $limit   = $limit > 0 ? min($limit, 50) : 10;

        $builder = $this->modelProduk
            ->select('id, name, sku, current_stock, unit, min_stock')
            ->where('is_active', true);

        if ($keyword !== '') {
            $builder->groupStart()
                ->like('name', $keyword)
                ->orLike('sku', $keyword)
                ->groupEnd();
        }

        $products = $builder->orderBy('name', 'ASC')->findAll($limit);

        return $this->jsonResponse([
            'status' => true,
            'data'   => $products,
        ]);
    }

    /**
     * GET /api/products/stock-status/{id}
     */
    public function getStockStatus($id)
    {
        $product = $this->modelProduk->find((int) $id);
        if (!$product) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        $currentStock = (int) ($product['current_stock'] ?? 0);
        $minStock     = (int) ($product['min_stock'] ?? 0);

        $status = 'normal';
        if ($currentStock <= 0) {
            $status = 'habis';
        } elseif ($currentStock <= $minStock) {
            $status = 'rendah';
        }

        return $this->jsonResponse([
            'status' => true,
            'data'   => [
                'product_id'    => (int) $product['id'],
                'current_stock' => $currentStock,
                'min_stock'     => $minStock,
                'stock_status'  => $status,
            ],
        ]);
    }

    /**
     * GET /api/products/by-category/{categoryId}
     */
    public function getByCategory($categoryId)
    {
        $products = $this->modelProduk
            ->select('id, name, sku, current_stock, unit')
            ->where('is_active', true)
            ->where('category_id', (int) $categoryId)
            ->orderBy('name', 'ASC')
            ->findAll();

        return $this->jsonResponse([
            'status' => true,
            'data'   => $products,
        ]);
    }
}

