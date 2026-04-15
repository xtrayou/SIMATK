<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\BarangModel;

class BerandaController extends BaseController
{
    protected BarangModel  $modelBarang;
    protected KategoriModel $modelKategori;

    public function __construct()
    {
        $this->modelBarang   = new BarangModel();
        $this->modelKategori = new KategoriModel();
    }

    /**
     * Landing Page — tampilkan daftar barang & kategori dari DB
     */
    public function index()
    {
        // Jika sudah login, langsung ke dashboard
        // if (session()->get('isLoggedIn')) {
        //     return redirect()->to('/dashboard');
        // }

        $daftarBarang = $this->modelBarang->getBarangAktif();
        $daftarKategori = $this->modelKategori->getKategoriAktif();

        foreach ($daftarBarang as &$barang) {
            $stokBarang = (int) ($barang['current_stock'] ?? 0);
            if ($stokBarang <= 0) {
                $barang['status_label'] = '🔴 Perlu pengadaan';
            } elseif ($stokBarang <= 10) {
                $barang['status_label'] = '🟡 Terbatas';
            } else {
                $barang['status_label'] = '🟢 Tersedia';
            }
        }
        unset($barang);

        $oldInput = session()->getFlashdata('_ci_old_input') ?? [];
        $oldProductId = (string) ($oldInput['product_id'] ?? '');
        $oldProductName = '';
        foreach ($daftarBarang as $barang) {
            if ((string) ($barang['id'] ?? '') === $oldProductId) {
                $oldProductName = (string) ($barang['name'] ?? '');
                break;
            }
        }

        $kategoriPreview = array_slice($daftarKategori, 0, 12);
        $kategoriLainnya = array_slice($daftarKategori, 12);
        $kodeResiPopup = session()->getFlashdata('kode_resi') ?: $this->request->getGet('resi');

        $unitKerja = config('App')->unitKerja ?? [
            'Sistem Informasi',
            'Informatika',
            'TU Fakultas',
            'Lainnya',
        ];

        return view('home/index', [
            'daftarBarang'   => $daftarBarang,
            'daftarKategori' => $daftarKategori,
            'unitKerja'      => $unitKerja,
            'oldProductName' => $oldProductName,
            'kategoriPreview' => $kategoriPreview,
            'kategoriLainnya' => $kategoriLainnya,
            'kodeResiPopup' => $kodeResiPopup,
            'stats'          => [
                'total_barang'   => $this->modelBarang->countAktif(),
                'total_kategori' => $this->modelKategori->countAktif(),
                'total_laporan'  => get_total_laporan_bulanan(),
                'jam_operasi'   => 8 // Tetap hardcoded atau dari config
            ]
        ]);
    }
}
