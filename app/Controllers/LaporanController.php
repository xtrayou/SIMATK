<?php

namespace App\Controllers;

use App\Services\LaporanService;

class LaporanController extends BaseController
{
    protected LaporanService $laporanService;

    public function __construct()
    {
        $this->laporanService = new LaporanService();
    }

    /**
     * Stock Report - Current inventory status
     */
    public function stock()
    {
        $data = $this->laporanService->stock($this->request);

        if (($data['report_mode'] ?? 'stock') === 'opname') {
            $this->setPageData('Stock Opname', 'Data hasil pengecekan fisik stok pada periode tertentu.');
        } else {
            $this->setPageData('Stok Saat Ini', 'Menampilkan jumlah stok barang saat ini.');
        }

        return $this->render('reports/stock', $data);
    }

    /**
     * Movement Report - Stock movement analysis
     */
    public function movements()
    {
        $this->setPageData('Laporan Pergerakan', 'Analisis pergerakan stok dalam periode tertentu');

        $data = $this->laporanService->movements($this->request);

        return $this->render('reports/movements', $data);
    }

    /**
     * Valuation Report - Inventory valuation analysis
     */
    public function valuation()
    {
        $this->setPageData('Nilai Persediaan', 'Analisis nilai inventory dan profitability');

        $data = $this->laporanService->valuation($this->request);

        return $this->render('reports/valuation', $data);
    }

    /**
     * Analytics Dashboard
     */
    public function analytics()
    {
        $this->setPageData('Analytics Dashboard', 'Advanced analytics dan insights bisnis');

        $data = $this->laporanService->analytics($this->request);

        return $this->render('reports/analytics', $data);
    }

    /**
     * Export Reports
     */
    public function exportStock($format = 'excel')
    {
        try {
            $this->laporanService->exportStock($this->request, (string) $format);
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * Export Movement Reports
     */
    public function exportMovements($format = null)
    {
        try {
            $this->laporanService->exportMovements(
                $this->request,
                $format !== null ? (string) $format : null
            );
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back();
    }
}
