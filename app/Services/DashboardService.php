<?php

namespace App\Services;

use App\Models\MasterData\BarangModel;
use App\Models\MasterData\KategoriModel;
use App\Models\Stok\MutasiStokModel;

class DashboardService
{
    protected BarangModel $modelBarang;
    protected KategoriModel $modelKategori;
    protected MutasiStokModel $modelMutasiStok;

    public function __construct()
    {
        $this->modelBarang = new BarangModel();
        $this->modelKategori = new KategoriModel();
        $this->modelMutasiStok = new MutasiStokModel();
    }

    /**
     * Kumpulkan seluruh data dashboard utama.
     */
    public function getDashboardData(): array
    {
        $stats = [
            'total_products' => $this->modelBarang->countAktif(),
            'total_categories' => $this->modelKategori->countAktif(),
            'inventory_value' => $this->modelBarang->getTotalNilaiInventaris(),
            'low_stock_count' => $this->modelBarang->countStokRendah(),
            'out_of_stock_count' => $this->modelBarang->countStokHabis(),
        ];

        return array_merge($stats, [
            'recent_movements' => $this->modelMutasiStok->getMutasiDenganBarang(10),
            'low_stock_products' => $this->modelBarang->getBarangStokRendah(8),
            'top_products' => $this->modelBarang->getTopProductsByValue(5),
            'chart_data' => $this->getChartData(),
            'quick_stats' => $this->getStatistikCepat(),
        ]);
    }

    /**
     * Ambil statistik cepat mingguan.
     */
    public function getStatistikCepat(): array
    {
        $today = date('Y-m-d');
        $thisWeek = date('Y-m-d', strtotime('-7 days'));

        return [
            'today_movements' => $this->modelMutasiStok->countMutasiByDate($today),
            'this_week_in' => $this->modelMutasiStok->getTotalQuantityByTypeSince('IN', $thisWeek),
            'this_week_out' => $this->modelMutasiStok->getTotalQuantityByTypeSince('OUT', $thisWeek),
        ];
    }

    /**
     * Gabungkan semua data chart dashboard.
     */
    public function getChartData(): array
    {
        return [
            'monthly_movements' => $this->getDataChartMutasiBulanan(),
            'category_distribution' => $this->getDataChartDistribusiKategori(),
            'stock_status_pie' => $this->getDataChartStatusStok(),
        ];
    }

    /**
     * Siapkan data chart mutasi bulanan.
     */
    private function getDataChartMutasiBulanan(): array
    {
        $movements = $this->modelMutasiStok->getMutasiBulanan();
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $labels = [];
        $stockIn = array_fill(0, 6, 0);
        $stockOut = array_fill(0, 6, 0);

        for ($i = 5; $i >= 0; $i--) {
            $labels[] = $monthNames[(int) date('n', strtotime("-$i months")) - 1];
        }

        foreach ($movements as $row) {
            $monthLabel = $monthNames[(int) $row['month'] - 1];
            $idx = array_search($monthLabel, $labels, true);
            if ($idx === false) {
                continue;
            }

            $qty = (int) $row['total_quantity'];
            if ($row['type'] === 'IN') {
                $stockIn[$idx] = $qty;
            } elseif ($row['type'] === 'OUT') {
                $stockOut[$idx] = $qty;
            }
        }

        return [
            'labels' => $labels,
            'stock_in' => $stockIn,
            'stock_out' => $stockOut,
        ];
    }

    /**
     * Siapkan data chart distribusi kategori.
     */
    private function getDataChartDistribusiKategori(): array
    {
        $categories = $this->modelKategori->getDistribusiUntukDashboard();

        return [
            'labels' => array_column($categories, 'name'),
            'data' => array_map('intval', array_column($categories, 'product_count')),
            'values' => array_map('floatval', array_column($categories, 'total_value')),
        ];
    }

    /**
     * Siapkan data chart pie status stok.
     */
    private function getDataChartStatusStok(): array
    {
        return [
            'labels' => ['Habis', 'Stok Rendah', 'Normal'],
            'data' => [
                $this->modelBarang->countStokHabis(),
                $this->modelBarang->countStokRendah(),
                $this->modelBarang->countStokNormal(),
            ],
            'colors' => ['#dc3545', '#ffc107', '#198754'],
        ];
    }
}
