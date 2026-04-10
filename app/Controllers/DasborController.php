<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KategoriModel;
use App\Models\ProdukModel;
use App\Models\MutasiStokModel;

/**
 * DasborController - Controller untuk halaman dashboard utama
 */
class DasborController extends BaseController
{
    protected ProdukModel $modelProduk;
    protected KategoriModel $modelKategori;
    protected MutasiStokModel $modelMutasiStok;

    public function __construct()
    {
        $this->modelProduk        = new ProdukModel();
        $this->modelKategori       = new KategoriModel();
        $this->modelMutasiStok  = new MutasiStokModel();
    }

    public function index()
    {
        $this->setPageData('Dashboard', 'Ringkasan sistem inventaris dan statistik');

        $stats = [
            'total_products'     => $this->hitungProdukAktif(),
            'total_categories'   => $this->hitungKategoriAktif(),
            'inventory_value'    => $this->modelProduk->getTotalNilaiInventaris(),
            'low_stock_count'    => count($this->modelProduk->getProdukStokRendah()),
            'out_of_stock_count' => $this->hitungProdukStokHabis(),
        ];

        $data = array_merge($stats, [
            'recent_movements'    => $this->modelMutasiStok->getMutasiDenganProduk(10),
            'low_stock_products'  => $this->modelProduk->getProdukStokRendah(8),
            'top_products'        => $this->getProdukTeratasBerdasarkanNilai(5),
            'chart_data'          => [
                'monthly_movements'      => $this->getDataChartMutasiBulanan(),
                'category_distribution'  => $this->getDataChartDistribusiKategori(),
                'stock_status_pie'       => $this->getDataChartStatusStok(),
            ],
            'quick_stats' => $this->getStatistikCepat(),
        ]);

        return $this->render('dashboard/index', $data);
    }

    /**
     * Hitung produk aktif
     */
    private function hitungProdukAktif(): int
    {
        return $this->modelProduk->where('is_active', true)->countAllResults();
    }

    /**
     * Hitung kategori aktif
     */
    private function hitungKategoriAktif(): int
    {
        return $this->modelKategori->where('is_active', true)->countAllResults();
    }

    /**
     * Hitung produk yang stoknya habis
     */
    private function hitungProdukStokHabis(): int
    {
        return $this->modelProduk
            ->where('IFNULL(stock_baik, current_stock) <= 0', null, false)
            ->where('is_active', true)
            ->countAllResults();
    }

    /**
     * Ambil statistik cepat mingguan
     */
    private function getStatistikCepat(): array
    {
        $today = date('Y-m-d');
        $thisWeek = date('Y-m-d', strtotime('-7 days'));

        $totalIn = $this->modelMutasiStok->where('type', 'IN')
            ->where('created_at >=', $thisWeek)
            ->selectSum('quantity', 'total')->first()['total'] ?? 0;

        $totalOut = $this->modelMutasiStok->where('type', 'OUT')
            ->where('created_at >=', $thisWeek)
            ->selectSum('quantity', 'total')->first()['total'] ?? 0;

        return [
            'today_movements' => $this->modelMutasiStok->where("DATE(created_at)", $today, false)->countAllResults(),
            'this_week_in'    => (int) $totalIn,
            'this_week_out'   => (int) $totalOut,
        ];
    }

    /**
     * Ambil produk teratas berdasarkan nilai finansial
     */
    private function getProdukTeratasBerdasarkanNilai(int $limit = 5): array
    {
        return $this->modelProduk->select('
                products.*,
                categories.name as category_name,
                (products.current_stock * products.price) as total_value
            ')
            ->join('categories', 'categories.id = products.category_id')
            ->where('products.is_active', true)
            ->where('products.current_stock >', 0)
            ->orderBy('total_value', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Siapkan data chart mutasi bulanan
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
            if ($idx === false) continue;

            $qty = (int) $row['total_quantity'];
            if ($row['type'] === 'IN') {
                $stockIn[$idx] = $qty;
            } elseif ($row['type'] === 'OUT') {
                $stockOut[$idx] = $qty;
            }
        }

        return ['labels' => $labels, 'stock_in' => $stockIn, 'stock_out' => $stockOut];
    }

    /**
     * Siapkan data chart distribusi kategori
     */
    private function getDataChartDistribusiKategori(): array
    {
        $categories = $this->modelKategori->select('
                categories.name,
                COUNT(products.id) as product_count,
                SUM(products.current_stock * products.price) as total_value
            ')
            ->join('products', 'products.category_id = categories.id', 'left')
            ->where('categories.is_active', true)
            ->groupBy('categories.id')
            ->orderBy('product_count', 'DESC')
            ->findAll();

        return [
            'labels' => array_column($categories, 'name'),
            'data'   => array_map('intval', array_column($categories, 'product_count')),
            'values' => array_map('floatval', array_column($categories, 'total_value')),
        ];
    }

    /**
     * Siapkan data chart pie status stok
     */
    private function getDataChartStatusStok(): array
    {
        $outOfStock = $this->modelProduk
            ->where('IFNULL(stock_baik, current_stock) <= 0', null, false)
            ->where('is_active', true)
            ->countAllResults();

        $lowStock = $this->modelProduk
            ->where('IFNULL(stock_baik, current_stock) <= products.min_stock', null, false)
            ->where('IFNULL(stock_baik, current_stock) > 0', null, false)
            ->where('is_active', true)
            ->countAllResults();

        $normalStock = $this->modelProduk
            ->where('IFNULL(stock_baik, current_stock) > products.min_stock', null, false)
            ->where('is_active', true)
            ->countAllResults();

        return [
            'labels' => ['Habis', 'Stok Rendah', 'Normal'],
            'data'   => [$outOfStock, $lowStock, $normalStock],
            'colors' => ['#dc3545', '#ffc107', '#198754'],
        ];
    }
}
