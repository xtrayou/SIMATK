<?php

namespace App\Controllers\Beranda;

use App\Controllers\BaseController;
use App\Models\MasterData\KategoriModel;
use App\Models\MasterData\BarangModel;

class BerandaController extends BaseController
{
    protected BarangModel   $modelBarang;
    protected KategoriModel $modelKategori;

    public function __construct()
    {
        $this->modelBarang   = new BarangModel();
        $this->modelKategori = new KategoriModel();
    }

    /**
     * Landing Page – tampilkan form permintaan, daftar barang & kategori.
     */
    public function index()
    {
        $daftarBarang   = $this->modelBarang->getBarangAktif();
        $daftarKategori = $this->modelKategori->getKategoriAktif();

        // Tambahkan label status stok ke setiap barang
        foreach ($daftarBarang as &$barang) {
            $stok = (int) ($barang['current_stock'] ?? 0);
            $barang['status_label'] = match (true) {
                $stok <= 0  => '🔴 Perlu pengadaan',
                $stok <= 10 => '🟡 Terbatas',
                default     => '🟢 Tersedia',
            };
        }
        unset($barang);

        // Cari nama produk dari old input (setelah validasi gagal)
        $oldProductId   = (string) (old('product_id') ?? '');
        $oldProductName = '';
        foreach ($daftarBarang as $barang) {
            if ((string) ($barang['id'] ?? '') === $oldProductId) {
                $oldProductName = (string) ($barang['name'] ?? '');
                break;
            }
        }

        return view('home/index', [
            'daftarBarang'    => $daftarBarang,
            'daftarKategori'  => $daftarKategori,
            'unitKerja'       => config('App')->unitKerja ?? ['Sistem Informasi', 'Informatika', 'TU Fakultas', 'Lainnya'],
            'oldProductName'  => $oldProductName,
            'kategoriPreview' => array_slice($daftarKategori, 0, 12),
            'kategoriLainnya' => array_slice($daftarKategori, 12),
            'stats'           => [
                'total_barang'   => $this->modelBarang->countAktif(),
                'total_kategori' => $this->modelKategori->countAktif(),
                'jam_operasi'    => 8,
            ],
        ]);
    }
}
