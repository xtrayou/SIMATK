<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\MutasiStokModel;
use App\Models\ProdukModel;
use Exception;

class StokController extends BaseController
{
    protected ProdukModel $modelProduk;
    protected MutasiStokModel $modelMutasiStok;

    public function __construct()
    {
        $this->modelProduk = new ProdukModel();
        $this->modelMutasiStok = new MutasiStokModel();
    }

    /**
     * GET /api/product/{id}/info
     */
    public function getProductInfo($id)
    {
        $product = $this->modelProduk->getProductWithCategory((int) $id);
        if (!$product) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        return $this->jsonResponse([
            'status' => true,
            'data'   => $product,
        ]);
    }

    /**
     * GET /api/alerts/count
     */
    public function getAlertsCount()
    {
        $outOfStock = $this->modelProduk->where('is_active', true)->where('current_stock', 0)->countAllResults();
        $lowStock   = count($this->modelProduk->getLowStockProducts());

        return $this->jsonResponse([
            'status' => true,
            'data'   => [
                'out_of_stock' => $outOfStock,
                'low_stock'    => $lowStock,
                'total_alerts' => $outOfStock + $lowStock,
            ],
        ]);
    }

    /**
     * POST /api/bulk/in
     */
    public function bulkStockIn()
    {
        return $this->processBulkMovement('IN');
    }

    /**
     * POST /api/bulk/out
     */
    public function bulkStockOut()
    {
        return $this->processBulkMovement('OUT');
    }

    private function processBulkMovement(string $type)
    {
        $jsonBody  = $this->request->getJSON(true);
        $movements = (array) ($jsonBody['movements'] ?? $this->request->getPost('movements') ?? []);
        if (empty($movements)) {
            return $this->jsonResponse([
                'status'  => false,
                'message' => 'Data mutasi tidak boleh kosong.',
            ], 422);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $reference = $this->modelMutasiStok->generateReferenceNo($type);
            $success   = 0;

            foreach ($movements as $item) {
                $productId = (int) ($item['product_id'] ?? 0);
                $quantity  = (int) ($item['quantity'] ?? 0);

                if ($productId <= 0 || $quantity <= 0) {
                    continue;
                }

                $this->modelMutasiStok->createMovement([
                    'product_id'   => $productId,
                    'type'         => $type,
                    'quantity'     => $quantity,
                    'reference_no' => $item['reference_no'] ?? $reference,
                    'notes'        => $item['notes'] ?? null,
                    'created_by'   => session()->get('userId') ?: null,
                ]);
                $success++;
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new Exception('Gagal memproses mutasi stok.');
            }

            return $this->jsonResponse([
                'status'  => true,
                'message' => 'Mutasi stok berhasil diproses.',
                'data'    => [
                    'type'          => $type,
                    'reference_no'  => $reference,
                    'success_count' => $success,
                ],
            ]);
        } catch (Exception $e) {
            $db->transRollback();

            return $this->jsonResponse([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
