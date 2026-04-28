<?php

namespace App\Models\Stok;

use CodeIgniter\Model;
use Exception;
use App\Models\MasterData\BarangModel;

/**
 * MutasiStokModel - Model untuk mengelola pergerakan stok (mutasi masuk/keluar/penyesuaian)
 *
 * Relasi:
 * - PergerakanStok terkait Barang (stock_movements.product_id → products.id)
 */
class MutasiStokModel extends Model
{
    protected $table = 'stock_movements';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'product_id',
        'type',
        'quantity',
        'previous_stock',
        'reference_no',
        'notes',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    private function getSnapshotStok(array $barang): array
    {
        $stokSebelumnya = max(0, (int) ($barang['current_stock'] ?? 0));
        $stokBaikSebelumnya = max(0, (int) ($barang['stock_baik'] ?? $stokSebelumnya));
        $stokRusakSebelumnya = max(0, (int) ($barang['stock_rusak'] ?? 0));

        // Jika data lama tidak sinkron, fallback ke semua stok dianggap baik.
        if (($stokBaikSebelumnya + $stokRusakSebelumnya) !== $stokSebelumnya) {
            $stokBaikSebelumnya = $stokSebelumnya;
            $stokRusakSebelumnya = 0;
        }

        return [
            'total' => $stokSebelumnya,
            'baik'  => $stokBaikSebelumnya,
            'rusak' => $stokRusakSebelumnya,
        ];
    }

    private function handleStockIn(array $snapshot, array $data): array
    {
        $qty = max(0, (int) ($data['quantity'] ?? 0));
        $rusak = max(0, (int) ($data['damaged_quantity'] ?? 0));

        if ($qty <= 0 && $rusak <= 0) {
            throw new Exception('Jumlah barang masuk harus lebih dari 0');
        }

        $stokBaikBaru = $snapshot['baik'] + $qty;
        $stokRusakBaru = $snapshot['rusak'] + $rusak;

        return [
            'quantity' => $qty + $rusak,
            'baik'     => $stokBaikBaru,
            'rusak'    => $stokRusakBaru,
            'total'    => $stokBaikBaru + $stokRusakBaru,
        ];
    }

    private function handleStockOut(array $snapshot, array $barang, int $qty): array
    {
        if ($qty <= 0) {
            throw new Exception('Jumlah barang keluar harus lebih dari 0');
        }

        if ($snapshot['baik'] < $qty) {
            throw new Exception('Stok baik tidak mencukupi untuk ' . ($barang['name'] ?? 'barang ini'));
        }

        $stokBaikBaru = $snapshot['baik'] - $qty;

        return [
            'quantity' => $qty,
            'baik'     => $stokBaikBaru,
            'rusak'    => $snapshot['rusak'],
            'total'    => $stokBaikBaru + $snapshot['rusak'],
        ];
    }

    private function handleStockAdjustment(array $data, int $qty): array
    {
        $punyaSplitStock = array_key_exists('adjusted_good_stock', $data)
            || array_key_exists('adjusted_damaged_stock', $data);

        if ($punyaSplitStock) {
            $stokBaikBaru = max(0, (int) ($data['adjusted_good_stock'] ?? 0));
            $stokRusakBaru = max(0, (int) ($data['adjusted_damaged_stock'] ?? 0));
            $stokBaru = $stokBaikBaru + $stokRusakBaru;

            return [
                'quantity' => $stokBaru,
                'baik'     => $stokBaikBaru,
                'rusak'    => $stokRusakBaru,
                'total'    => $stokBaru,
            ];
        }

        // Backward-compatible: quantity dianggap stok akhir total.
        $stokBaru = $qty;

        return [
            'quantity' => $stokBaru,
            'baik'     => $stokBaru,
            'rusak'    => 0,
            'total'    => $stokBaru,
        ];
    }

    /**
     * Ambil data mutasi stok beserta detail barang
     *
     * @param int   $batas  Batas jumlah data (0 = semua)
     * @param array $filter Filter berupa product_id, type, start_date, end_date
     * @return array Daftar mutasi stok
     */
    public function getMutasiDenganBarang(int $batas = 10, array $filter = []): array
    {
        $builder = $this->select('stock_movements.*, barang.name as product_name, barang.sku as product_sku, barang.unit')
            ->select('CASE 
                WHEN stock_movements.type = "IN" THEN stock_movements.previous_stock + stock_movements.quantity
                WHEN stock_movements.type = "OUT" THEN stock_movements.previous_stock - stock_movements.quantity
                WHEN stock_movements.type = "ADJUSTMENT" THEN stock_movements.quantity
                ELSE stock_movements.previous_stock
            END as current_stock', false)
            ->join('barang', 'barang.id = stock_movements.product_id');

        if (!empty($filter['product_id'])) {
            $builder->where('stock_movements.product_id', $filter['product_id']);
        }

        if (!empty($filter['type'])) {
            $builder->where('stock_movements.type', $filter['type']);
        }

        if (!empty($filter['start_date'])) {
            $builder->where('DATE(stock_movements.created_at) >=', $filter['start_date']);
        }

        if (!empty($filter['end_date'])) {
            $builder->where('DATE(stock_movements.created_at) <=', $filter['end_date']);
        }

        $builder->orderBy('stock_movements.created_at', 'DESC');

        if ($batas > 0) {
            $builder->limit($batas);
        }

        return $builder->findAll();
    }

    /**
     * Ambil ringkasan mutasi stok per bulan untuk chart
     *
     * @return array Data mutasi bulanan
     */
    public function getMutasiBulanan(): array
    {
        $enamBulanLalu = date('Y-m-01', strtotime('-5 months'));

        return $this->select("
                MONTH(created_at) as month,
                type,
                SUM(quantity) as total_quantity
            ")
            ->where('created_at >=', $enamBulanLalu)
            ->groupBy('MONTH(created_at), type')
            ->orderBy('month', 'ASC')
            ->findAll();
    }

    /**
     * Hitung total kuantitas mutasi berdasarkan tipe sejak tanggal tertentu.
     */
    public function getTotalQuantityByTypeSince(string $type, string $startDate): int
    {
        $result = $this->where('type', $type)
            ->where('created_at >=', $startDate)
            ->selectSum('quantity', 'total')
            ->first();

        return (int) ($result['total'] ?? 0);
    }

    /**
     * Hitung total mutasi pada tanggal tertentu.
     */
    public function countMutasiByDate(string $date): int
    {
        return $this->where('DATE(created_at)', $date, false)
            ->countAllResults();
    }

    /**
     * Buat mutasi stok baru dan perbarui stok barang
     *
     * @param array $data Data mutasi (product_id, type, quantity, reference_no, notes, created_by)
     * @return int ID mutasi yang baru dibuat
     * @throws Exception Jika barang tidak ditemukan atau stok tidak mencukupi
     */
    public function buatMutasi(array $data): int
    {
        // 1. Ambil barang
        $modelBarang = new BarangModel();
        $barang = $modelBarang->find($data['product_id'] ?? null);

        if (!$barang) {
            throw new Exception('Barang tidak ditemukan');
        }

        $snapshot = $this->getSnapshotStok($barang);

        // 2. Hitung stok baru
        $tipe = strtoupper((string) ($data['type'] ?? ''));
        $jumlah = max(0, (int) ($data['quantity'] ?? 0));

        if ($tipe === 'IN') {
            $hasilStok = $this->handleStockIn($snapshot, $data);
        } elseif ($tipe === 'OUT') {
            $hasilStok = $this->handleStockOut($snapshot, $barang, $jumlah);
        } elseif ($tipe === 'ADJUSTMENT') {
            $hasilStok = $this->handleStockAdjustment($data, $jumlah);
        } else {
            throw new Exception('Tipe mutasi tidak valid');
        }

        $data['type'] = $tipe;
        $data['quantity'] = $hasilStok['quantity'];
        $data['previous_stock'] = $snapshot['total'];
        unset($data['damaged_quantity'], $data['adjusted_good_stock'], $data['adjusted_damaged_stock']);

        // 3. Simpan mutasi
        $idMutasi = $this->insert($data);
        if (!$idMutasi) {
            throw new Exception('Gagal menyimpan mutasi stok');
        }

        // 4. Update stok barang
        $modelBarang->update($data['product_id'], [
            'current_stock' => $hasilStok['total'],
            'stock_baik'    => $hasilStok['baik'],
            'stock_rusak'   => $hasilStok['rusak'],
        ]);

        return (int) $idMutasi;
    }

    /**
     * Generate nomor referensi otomatis
     *
     * @param string $tipe Tipe mutasi (IN, OUT, ADJUSTMENT)
     * @return string Nomor referensi unik
     */
    public function generateNomorReferensi(string $tipe = 'IN'): string
    {
        $prefix = match ($tipe) {
            'IN'         => 'SM-IN',
            'OUT'        => 'SM-OUT',
            'ADJUSTMENT' => 'SM-ADJ',
            default      => 'SM',
        };

        $hariIni = date('Ymd');
        $terakhir = $this->like('reference_no', $prefix . '-' . $hariIni, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        $nomor = 1;
        if ($terakhir) {
            $bagian = explode('-', $terakhir['reference_no']);
            $bagianTerakhir = end($bagian);
            if (is_numeric($bagianTerakhir)) {
                $nomor = (int) $bagianTerakhir + 1;
            }
        }

        return $prefix . '-' . $hariIni . '-' . str_pad((string)$nomor, 4, '0', STR_PAD_LEFT);
    }
}
