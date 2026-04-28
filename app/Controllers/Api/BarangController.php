<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\MasterData\BarangModel;

class BarangController extends BaseController
{
    protected BarangModel $modelBarang;

    public function __construct()
    {
        $this->modelBarang = new BarangModel();
    }

    /**
     * GET /api/products/search?q=...
     */
    public function search()
    {
        $keyword = trim((string) ($this->request->getGet('q') ?? ''));
        $limit   = (int) ($this->request->getGet('limit') ?? 10);
        $limit   = $limit > 0 ? min($limit, 50) : 10;

        $builder = $this->modelBarang
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
     * GET /api/products/autofill?kode=... atau ?nama=...
     */
    public function autofill()
    {
        $kode = trim((string) ($this->request->getGet('kode') ?? ''));
        $nama = trim((string) ($this->request->getGet('nama') ?? ''));

        if ($kode === '' && $nama === '') {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Parameter kode atau nama wajib diisi.',
            ], 400);
        }

        $query = $this->modelBarang
            ->select('id, sku, name, unit, price, current_stock, min_stock')
            ->where('is_active', true);

        if ($kode !== '') {
            if (strlen($kode) < 3) {
                return $this->jsonResponse([
                    'status'  => false,
                    'message' => 'Minimal 3 karakter untuk pencarian kode.',
                ], 400);
            }

            $query->groupStart()
                ->where('sku', $kode)
                ->orLike('sku', $kode, 'after')
                ->groupEnd()
                ->orderBy('CASE WHEN sku = ' . $this->modelBarang->escape($kode) . ' THEN 0 ELSE 1 END', '', false)
                ->orderBy('sku', 'ASC');
        }

        if ($nama !== '') {
            if (strlen($nama) < 3) {
                return $this->jsonResponse([
                    'status'  => false,
                    'message' => 'Minimal 3 karakter untuk pencarian nama.',
                ], 400);
            }

            $query->groupStart()
                ->where('name', $nama)
                ->orLike('name', $nama)
                ->groupEnd()
                ->orderBy('CASE WHEN name = ' . $this->modelBarang->escape($nama) . ' THEN 0 ELSE 1 END', '', false)
                ->orderBy('name', 'ASC');
        }

        $product = $query->first();

        if (!$product) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Data barang tidak ditemukan.',
            ], 404);
        }

        return $this->jsonResponse([
            'status' => true,
            'data'   => [
                'id'            => (int) ($product['id'] ?? 0),
                'kode_barang'   => (string) ($product['sku'] ?? ''),
                'nama_barang'   => (string) ($product['name'] ?? ''),
                'satuan'        => (string) ($product['unit'] ?? ''),
                'harga'         => (float) ($product['price'] ?? 0),
                'stok'          => (int) ($product['current_stock'] ?? 0),
                'stok_minimum'  => (int) ($product['min_stock'] ?? 0),
            ],
        ]);
    }

    /**
     * GET /api/products/stock-status/{id}
     */
    public function getStockStatus($id)
    {
        $product = $this->modelBarang->find((int) $id);
        if (!$product) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Barang tidak ditemukan.',
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
        $products = $this->modelBarang
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
