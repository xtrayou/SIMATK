<?php

namespace App\Controllers;

use App\Models\KodeBarangModel;

class KodeBarangController extends BaseController
{
    public function index()
    {
        $this->setPageData('Daftar Kode Barang', 'Referensi standar kode barang berdasar Peraturan Bupati');

        $model = new KodeBarangModel();

        $keyword = trim((string) ($this->request->getGet('q') ?? ''));

        $builder = $model->orderBy('kode', 'ASC');
        if ($keyword !== '') {
            $builder->groupStart()
                ->like('kode', $keyword)
                ->orLike('nama', $keyword)
                ->groupEnd();
        }

        $kode_barang = $builder->findAll();

        $data = [
            'daftarKode' => $kode_barang,
            'totalItem' => count($kode_barang),
            'keyword' => $keyword,
        ];

        return $this->render('kode_barang/index', $data);
    }
}
