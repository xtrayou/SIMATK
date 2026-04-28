<?php

namespace App\Controllers\MasterData;

use App\Controllers\BaseController;
use App\Models\MasterData\KodeBarangModel;

class KodeBarangController extends BaseController
{
    public function index()
    {
        $this->setPageData('Daftar Kode Barang', 'Referensi kode barang berdasarkan data produk');

        $model = new KodeBarangModel();

        $keyword = trim((string) ($this->request->getGet('q') ?? ''));

        $kode_barang = $model->cariKodeBarang($keyword);

        $data = [
            'daftarKode' => $kode_barang,
            'totalItem' => count($kode_barang),
            'keyword' => $keyword,
        ];

        return $this->render('kode_barang/index', $data);
    }
}
