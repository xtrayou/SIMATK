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

    public function store()
    {
        $rules = [
            'kode' => 'required|min_length[2]|max_length[50]',
            'nama' => 'required|min_length[3]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $id = $this->request->getPost('id');
        $data = [
            'kode' => trim((string)$this->request->getPost('kode')),
            'nama' => trim((string)$this->request->getPost('nama')),
        ];

        $model = new KodeBarangModel();

        if ($id) {
            $model->update($id, $data);
            return redirect()->back()->with('success', 'Kode Barang berhasil diperbarui.');
        } else {
            $model->insert($data);
            return redirect()->back()->with('success', 'Kode Barang berhasil ditambahkan.');
        }
    }

    public function delete($id)
    {
        $model = new KodeBarangModel();
        
        if ($model->delete($id)) {
            return redirect()->back()->with('success', 'Kode Barang berhasil dihapus.');
        }
        
        return redirect()->back()->with('error', 'Gagal menghapus Kode Barang.');
    }
}
