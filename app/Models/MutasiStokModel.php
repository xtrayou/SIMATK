<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

/**
 * MutasiStokModel - Model untuk mengelola pergerakan stok (mutasi masuk/keluar/penyesuaian)
 *
 * Relasi:
 * - PergerakanStok terkait Produk (stock_movements.product_id → products.id)
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

    /**
     * Ambil data mutasi stok beserta detail produk
     *
     * @param int   $batas  Batas jumlah data (0 = semua)
     * @param array $filter Filter berupa product_id, type, start_date, end_date
     * @return array Daftar mutasi stok
     */
    public function getMutasiDenganProduk(int $batas = 10, array $filter = []): array
    {
        $builder = $this->select('stock_movements.*, products.name as product_name, products.sku as product_sku, products.unit')
            ->select('CASE 
                WHEN stock_movements.type = "IN" THEN stock_movements.previous_stock + stock_movements.quantity
                WHEN stock_movements.type = "OUT" THEN stock_movements.previous_stock - stock_movements.quantity
                WHEN stock_movements.type = "ADJUSTMENT" THEN stock_movements.quantity
                ELSE stock_movements.previous_stock
            END as current_stock', false)
            ->join('products', 'products.id = stock_movements.product_id');

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
     * Buat mutasi stok baru dan perbarui stok produk
     *
     * @param array $data Data mutasi (product_id, type, quantity, reference_no, notes, created_by)
     * @return int ID mutasi yang baru dibuat
     * @throws Exception Jika produk tidak ditemukan atau stok tidak mencukupi
     */
    public function buatMutasi(array $data): int
    {
        $modelProduk = new ProdukModel();
        $produk = $modelProduk->find($data['product_id'] ?? null);

        if (!$produk) {
            throw new Exception('Produk tidak ditemukan');
        }

        $stokSebelumnya = max(0, (int) ($produk['current_stock'] ?? 0));
        $stokBaikSebelumnya = max(0, (int) ($produk['stock_baik'] ?? $stokSebelumnya));
        $stokRusakSebelumnya = max(0, (int) ($produk['stock_rusak'] ?? 0));

        // Jika data lama tidak sinkron, fallback ke semua stok dianggap baik.
        if (($stokBaikSebelumnya + $stokRusakSebelumnya) !== $stokSebelumnya) {
            $stokBaikSebelumnya = $stokSebelumnya;
            $stokRusakSebelumnya = 0;
        }

        $tipe = strtoupper((string) ($data['type'] ?? ''));
        $jumlah = max(0, (int) ($data['quantity'] ?? 0));

        switch ($tipe) {
            case 'IN':
                $jumlahRusakMasuk = max(0, (int) ($data['damaged_quantity'] ?? 0));
                if ($jumlah <= 0 && $jumlahRusakMasuk <= 0) {
                    throw new Exception('Jumlah barang masuk harus lebih dari 0');
                }

                $stokBaikBaru = $stokBaikSebelumnya + $jumlah;
                $stokRusakBaru = $stokRusakSebelumnya + $jumlahRusakMasuk;
                $stokBaru = $stokBaikBaru + $stokRusakBaru;
                $data['quantity'] = $jumlah + $jumlahRusakMasuk;
                break;
            case 'OUT':
                if ($jumlah <= 0) {
                    throw new Exception('Jumlah barang keluar harus lebih dari 0');
                }
                if ($stokBaikSebelumnya < $jumlah) {
                    throw new Exception('Stok baik tidak mencukupi untuk ' . ($produk['name'] ?? 'produk ini'));
                }

                $stokBaikBaru = $stokBaikSebelumnya - $jumlah;
                $stokRusakBaru = $stokRusakSebelumnya;
                $stokBaru = $stokBaikBaru + $stokRusakBaru;
                break;
            case 'ADJUSTMENT':
                $punyaSplitStock = array_key_exists('adjusted_good_stock', $data)
                    || array_key_exists('adjusted_damaged_stock', $data);

                if ($punyaSplitStock) {
                    $stokBaikBaru = max(0, (int) ($data['adjusted_good_stock'] ?? 0));
                    $stokRusakBaru = max(0, (int) ($data['adjusted_damaged_stock'] ?? 0));
                    $stokBaru = $stokBaikBaru + $stokRusakBaru;
                    $data['quantity'] = $stokBaru;
                } else {
                    // Backward-compatible: quantity dianggap stok akhir total.
                    $stokBaru = $jumlah;
                    $stokBaikBaru = $stokBaru;
                    $stokRusakBaru = 0;
                }
                break;
            default:
                throw new Exception('Tipe mutasi tidak valid');
        }

        $data['type'] = $tipe;
        $data['previous_stock'] = $stokSebelumnya;
        unset($data['damaged_quantity'], $data['adjusted_good_stock'], $data['adjusted_damaged_stock']);

        $idMutasi = $this->insert($data);
        if (!$idMutasi) {
            throw new Exception('Gagal menyimpan mutasi stok');
        }

        // Perbarui stok total beserta komposisi baik/rusak.
        $modelProduk->update($data['product_id'], [
            'current_stock' => $stokBaru,
            'stock_baik'    => $stokBaikBaru,
            'stock_rusak'   => $stokRusakBaru,
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
